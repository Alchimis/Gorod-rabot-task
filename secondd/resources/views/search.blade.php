<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    @vite(['resources/css/search.css'])
</head>
<body>
    <div class="container">
	<div class="container-card">
            <div class="search-bar">
                <div class="search-field">
                    <textarea name="search_input" class="search-input normal-text" id="search-input" placeholder="Поиск в пределах Москвы"></textarea>
                    <button type="submit" id="search-button" class="search-button">
                        <img src="{{asset("images/search-glass.svg")}}" class="search-icon">
                    </button>
                </div>
            </div>
            <div class="search-result">
            </div>
        </div>
	@vite(['resources/js/load.js'])
    </div>
</body>
</html>