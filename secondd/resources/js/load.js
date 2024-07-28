import './bootstrap';

export const HOST = "http://localhost:8080";

function infoComponent(content){
	return `<div class=\"info\">${content}</div>`;
}

function errorComponent(content){
	return `<div class=\"info error\">${content}</div>`;
}

export function sendSearchData(event){
    event.preventDefault();
    var searchElem = document.getElementById("search-input");
    var search = searchElem.value;
    var searchResult = document.querySelector(".search-result");
    searchResult.innerHTML = infoComponent("Ищем");
    window.axios.post(`${HOST}/api/searchComponent`, {
            search: search
    },{
    headers: {
        'Content-Type': 'application/json'
    }}).then(async response => {
        if (response.status >= 200 && response.status < 300){
            var searchResult = document.querySelector(".search-result");
            if (response.status === 204) {
                searchResult.innerHTML = infoComponent("Ничего не найдено");
                return;	   
            }
            searchResult.innerHTML = "";
            searchResult.innerHTML = response.data;
        } else if (response.status > 400) {
            var searchResult = document.querySelector(".search-result");
            searchResult.innerHTML = errorComponent("Ошибка при обработке запроса");
        }
    }).catch(e => {
        console.log(e);
        searchResult.innerHTML = errorComponent("ошибка");
    });
}

var button = document.getElementById("search-button");
button.onclick = sendSearchData;