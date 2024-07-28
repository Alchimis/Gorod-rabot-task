<div>
    <ul class="geobject-list">
        @foreach ( $geoObjects as $geoObject)
        <li class="shadow-box">
            <p class="geobject-title">{{ucfirst($geoObject->title)}}, {{ucfirst($geoObject->address)}}, ({{$geoObject->point}}), Тип: {{ucfirst($geoObject->kind)}}</p>
        </li>
        @endforeach
    </ul>
</div>
