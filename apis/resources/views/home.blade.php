<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTihzoJQQuljCu3cjTNd8nIaJ2IcKaUIw&callback=init"></script>
    <link rel="stylesheet" href="{!! url('css/styles.css') !!}" />
    <script src="{!! url('js/restaurants.js') !!}" ></script>


</head>

<body onload="init()">
    <div>
        <form name="frm_restaurants" id="frm_restaurants" autocomplete="on">
            Chennai Foods, Mohali<br />
            Latitude: <input name="lat" id="lat" type="text" value="30.72042">
            Longitude: <input name="lon" id="lon" type="text" value="76.70728"><br />
            Radius: <select name="rad" id="rad">
                <option value="1">1</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
            </select>
            <br />
            <input type="button" name="submit" id="findBtn" value="Find restaurants">
        </form>
        <table id="restaurants_list">
            <thead>
                <td>Sl.</td>
                <td>Restaurant</td>
                <td>Latitude</td>
                <td>Longitude</td>
                <td>Distance (kms)</td>
                <td>Time to travel</td>
            </thead>
        </table>
    </div>
</body>

</html>