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
    var apiUrl = 'api/v1/restaurant?lat=' + lat + '&lon=' + lon + '&rad=' + rad;
    var items = document.getElementById('restaurants_list').getElementsByTagName('tbody');

    while(items.length > 0) {
        items[0].remove();
    }

    fetchUrl(apiUrl, function(data) {
        var obj = JSON.parse(data);
        var row = "";

        if(obj.length) {
            for (i in obj) {
                row += "<tr><td>" + (parseInt(i) + 1) +
                    "</td><td>" + obj[i].name +
                    "</td><td>" + obj[i].lat +
                    "</td><td>" + obj[i].lon +
                    "</td><td>" + obj[i].distance +
                    "</td></tr>";
            }
        }
        else{
            row = "<tr><td colspan='5'>No restaurants found</td></tr>";
        }

        document.getElementById('restaurants_list').insertAdjacentHTML('beforeend',row);

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

