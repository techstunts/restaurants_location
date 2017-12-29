/**
 * Created by amit on 29/12/17.
 */
function init(){
    document.getElementById("findBtn").onclick = findRestaurants;
}

function findRestaurants(){
    var lat = document.getElementById('lat').value;
    var lon = document.getElementById('lon').value;
    var rad = document.getElementById('rad').value;
    var restaurantsApiUrl = 'api/v1/restaurant?lat=' + lat + '&lon=' + lon + '&rad=' + rad;
    var items = document.getElementById('restaurants_list').getElementsByTagName('tbody');

    while(items.length > 0) {
        items[0].remove();
    }

    fetchUrl(restaurantsApiUrl, function(data) {
        var obj = JSON.parse(data);
        var row = "";
        var destinationCoords = '';

        if(obj.length) {
            for (i in obj) {
                row += "<tr><td>" + (parseInt(i) + 1) +
                    "</td><td>" + obj[i].name +
                    "</td><td>" + obj[i].lat +
                    "</td><td>" + obj[i].lon +
                    "</td><td>" + Math.round(parseFloat(obj[i].distance) * 100) / 100 +
                    "</td><td>" +
                    "</td></tr>";

                destinationCoords += obj[i].lat + ',' + obj[i].lon + '|';
            }
        }
        else{
            row = "<tr><td colspan='5'>No restaurants found</td></tr>";
        }

        document.getElementById('restaurants_list').insertAdjacentHTML('beforeend',row);

        if(destinationCoords.length){

            var distanceApiUrl = 'http://maps.googleapis.com/maps/api/distancematrix/json?origins=' +
                lat + ',' + lon +
                '&destinations=' +
                destinationCoords.slice(0, -1) +
                '&mode=driving';

            fetchUrl(distanceApiUrl, function(data){
                var obj = JSON.parse(data);
                console.log(obj);

            });

            console.log(distanceApiUrl);
        }


    });



    //fetchUrl()

}


function fetchUrl(url, callback) {
    var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;

    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            request.onreadystatechange = function (){};
            callback(request.responseText, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}

