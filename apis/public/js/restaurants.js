/**
 * Created by amit on 29/12/17.
 */
function init(){
    document.getElementById("findBtn").onclick = findRestaurants;
}

function findRestaurants(){
    var lat = parseFloat(document.getElementById('lat').value);
    var lon = parseFloat(document.getElementById('lon').value);
    var rad = document.getElementById('rad').value;
    var restaurantsApiUrl = 'api/v1/restaurant?lat=' + lat + '&lon=' + lon + '&rad=' + rad;
    var items = document.getElementById('restaurants_list').getElementsByTagName('tbody');

    while(items.length > 0) {
        items[0].remove();
    }

    document.getElementById('restaurants_list').style.display = 'block';

    fetchUrl(restaurantsApiUrl, function(data) {
        var obj = JSON.parse(data);
        var row = "";
        var destinations = [];

        if(obj.length) {
            for (i in obj) {
                row += "<tr><td>" + (parseInt(i) + 1) +
                    "</td><td>" + obj[i].name +
                    "</td><td>" + obj[i].lat +
                    "</td><td>" + obj[i].lon +
                    "</td><td>" + Math.round(parseFloat(obj[i].distance) * 100) / 100 +
                    "</td><td class=\'distance_google\'>Loading ..." +
                    "</td><td class=\'timetotravel\'>Loading ..." +
                    "</td></tr>";

                destinations.push({lat: obj[i].lat, lng: obj[i].lon});
            }
        }
        else{
            row = "<tr><td colspan='5'>No restaurants found</td></tr>";
        }

        document.getElementById('restaurants_list').insertAdjacentHTML('beforeend',row);

        if(destinations.length){

            var service = new google.maps.DistanceMatrixService;
            service.getDistanceMatrix({
                origins: [{lat: lat, lng: lon}],
                destinations: destinations,
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false
            }, function(response, status) {
                if (status !== 'OK') {
                    alert('Error was: ' + status);
                } else {
                    var originList = response.originAddresses;
                    var destinationList = response.rows[0].elements;
                    var distance_google_tds = document.getElementsByClassName('distance_google');
                    var time_tds = document.getElementsByClassName('timetotravel');
                    for(j in destinationList){
                        distance_google_tds[j].innerHTML = destinationList[j].distance.text;
                        time_tds[j].innerHTML = destinationList[j].duration.text;
                    }
                }

            });

        }

    });

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

