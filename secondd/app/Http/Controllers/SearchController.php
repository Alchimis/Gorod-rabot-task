<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SearchResult;
use App\Models\GeoObject;
use App\View\Components\GeoCard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class SearchController extends Controller
{
    public function searchView(){
        return view("search");
    }
 
    public function search(Request $request){
        $search = $request->validate([
            "search" => "required|string"
        ])["search"];
        $searchResult = SearchResult::where('request', $search)->first();
        $geoObjects = [];
        if ($searchResult == null){
            $requestString = sprintf('https://geocode-maps.yandex.ru/1.x?apikey=%s&geocode=%s&lang=ru_RU&rspn=1&ll=37.423133,55.607899&spn=0.642695,0.463845&results=5&format=json',config("maps.api_key"),$search);
            $response = Http::get($requestString);
            switch ($response->status()){
                case 400:
                    Log::error("The request is missing a required parameter or the parameter value is incorrect.", ["response"=>$response]);
                    return response("Internal server error", 500);
                case 403:
                    Log::error("Invalid api key.",["request_string"=>$requestString]);
                    return response("Internal server error", 500);
                case 429:
                    Log::error("Too may request to https://geocode-maps.yandex.ru");
                    return response("Internal server error", 500);
            }
            $responseBody = $response->body();
            $parsedBody = [];
            try{
                $parsedBody = $this::parseBody($responseBody);
            } catch(\Exception $e){
                Log::error("Error with parsing response body", ["response_body"=>$responseBody, "e"=>$e]);
                return response("Internal server error", 500);
            }
            if ($parsedBody === null){
                Log::error("Error with parsing response body", ["response_body"=>$responseBody,]);
                return response("Internal server error", 500);
            }
            if (empty($parsedBody)){
                return response("No content",204);
            }

            try{
                DB::beginTransaction();
                $searchR = SearchResult::create(["request"=>$search, "result"=>$responseBody]);
                $searchRGeoObjects = $searchR->geoObjects();
                foreach($parsedBody as $parsedGeoObject){
                    $geoObject = GeoObject::where("title", $parsedGeoObject["name"])->first();
                    if ($geoObject == null) {
                        $geoObject = GeoObject::create([
                            "title"=>$parsedGeoObject["name"], 
                            "description" => $parsedGeoObject["description"],
                            "address"=>$parsedGeoObject["address"],
                            "kind"=>$parsedGeoObject["kind"],
                            "point"=>$parsedGeoObject["point"]]);
                    }
                    $geoObjects[] = $geoObject;
                    $searchRGeoObjects->attach($geoObject->id);
                }
                DB::commit();
            } catch(\Exception $e){
                DB::rollBack();
                Log::error($e);
                return response("Internal server error", 500);
            }
        } else {
            $geoObjects = $searchResult->geoObjects()->get();
        }
        return view('components.geo-card', ["geoObjects"=>$geoObjects]);
    }

    static private function parseBody(string $body): array|null {
        $rule = [
            'required_if:response.GeoObjectCollection.featureMember.*,<=,1',
            'string'
        ];
        $validator = Validator::make(json_decode($body, true), [
            'response.GeoObjectCollection.featureMember' => 'array',
            'response.GeoObjectCollection.featureMember.*.GeoObject.name' => $rule,
            'response.GeoObjectCollection.featureMember.*.GeoObject.description' => $rule,
            'response.GeoObjectCollection.featureMember.*.GeoObject.Point.pos' => $rule,
            'response.GeoObjectCollection.featureMember.*.GeoObject.metaDataProperty.GeocoderMetaData.kind' => $rule,
            'response.GeoObjectCollection.featureMember.*.GeoObject.metaDataProperty.GeocoderMetaData.text' => $rule,
        ]);
        if($validator->fails()){
            return null;
        };
        $parsedBody = $validator->validated();
        $objects = [];
        foreach ($parsedBody['response']['GeoObjectCollection']['featureMember'] as $geoObject){
            $geoObject = $geoObject["GeoObject"];
            $objects[] = [
                "name"=>$geoObject["name"],
                "description"=>$geoObject["description"],
                "point"=>$geoObject["Point"]["pos"],
                "kind"=>$geoObject["metaDataProperty"]["GeocoderMetaData"]["kind"],
                "address"=>$geoObject["metaDataProperty"]["GeocoderMetaData"]["text"],
            ];
        }
        return $objects;
    }
}
