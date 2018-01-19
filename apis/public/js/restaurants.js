/**
 * Created by amit on 29/12/17.
 */
function initLocationFinder(){
    document.getElementById("findBtn").onclick = findRestaurants;
}

function findRestaurants(){
    var lat = document.getElementById('lat').value;
    var lon = document.getElementById('lon').value;
    var rad = document.getElementById('rad').value;
    var count = document.getElementById('count').value;

    var restaurantsApiUrl = 'api/v1/restaurants?lat=' + lat + '&lon=' + lon + '&rad=' + rad + '&count=' + count;
    var items = document.getElementById('restaurants_list').getElementsByTagName('tbody');
    var origin = {lat: parseFloat(lat), lng: parseFloat(lon)};

    while(items.length > 0) {
        items[0].remove();
    }

    document.getElementById('restaurants_list').style.display = 'block';
    document.getElementById("timeFrontendBtn").onclick = '';
    document.getElementById("timeBackendBtn").onclick = '';

        fetchUrl(restaurantsApiUrl, function(data) {
        try {
            var obj = JSON.parse(data);
            var row = "";
            var destinations = [];

            if(obj.status == "OK" && obj.results.length) {

                for (i in obj.results) {
                    row += "<tr><td>" + (parseInt(i) + 1) +
                        "</td><td>" + obj.results[i].name +
                        "</td><td>" + obj.results[i].lat +
                        "</td><td>" + obj.results[i].lon +
                        "</td><td>" + Math.round(parseFloat(obj.results[i].distance) * 100) / 100 + ' km' +
                        "</td><td class=\'distance_google\'>" +
                        "</td><td class=\'timetotravel\'>" +
                        "</td><td class=\'timetotravel_bestguess\'>" +
                        "</td><td class=\'timetotravel_pessimistic\'>" +
                        "</td><td class=\'timetotravel_optimistic\'>" +
                        "</td></tr>";

                    destinations.push({lat: parseFloat(obj.results[i].lat), lng: parseFloat(obj.results[i].lon)});
                }
            }
            else{
                if(obj.status == "NOTOK")
                    row = "<tr><td colspan='7' class='alert alert-danger'>" + obj.message + "</td></tr>";
                else
                    row = "<tr><td colspan='7' class='alert alert-danger'>Unknown error!!</td></tr>";
            }
        } catch (e) {
            row = "<tr><td colspan='7' class='alert alert-danger'>Unknown error!!</td></tr>";
        }

        document.getElementById('restaurants_list').insertAdjacentHTML('beforeend',row);

        document.getElementById("timeFrontendBtn").onclick = getTimeToTravelFromFrontend(origin, destinations);
        document.getElementById("timeBackendBtn").onclick = getTimeToTravelFromBackend(origin, destinations);


    });

}


function getTimeToTravelFromBackend(origin, destinations){
    return function (){
        if(destinations.length){
            var traveltimetype = document.querySelector('input[name = "traveltimetype"]:checked').value;
            
            var div_timestamps = document.getElementById('div_timestamps');
            div_timestamps.innerHTML = "";

            var service = new google.maps.DistanceMatrixService;

            $.post("http://localhost:3000",
                {
                    origins: JSON.stringify([origin]),
                    destinations: JSON.stringify(destinations),
                    trafficModel: traveltimetype
                },
                function(data, status){
                    console.log(status);
                    console.log(JSON.parse(data));

                    if (status !== 'success') {
                        alert('Error was: ' + status);
                    } else {
                        var destinationList = JSON.parse(data).results;
                        var distance_google_tds = document.getElementsByClassName('distance_google');
                        var time_tds = document.getElementsByClassName('timetotravel');
                        var traffic_time_tds = document.getElementsByClassName('timetotravel_' + traveltimetype);
                        for(j in destinationList){
                            distance_google_tds[j].innerHTML = destinationList[j].distance.text;
                            time_tds[j].innerHTML = destinationList[j].duration.text;
                            traffic_time_tds[j].innerHTML = destinationList[j].duration_in_traffic.text;
                        }

                        tss = JSON.parse(data).timestamps;
                        for(k in tss){
                            div_timestamps.innerHTML += tss[k] + "<br />";
                        }
                    }
                }
            );
        }
    }
};


function getTimeToTravelFromFrontend(origin, destinations){
    return function (){
        if(destinations.length){
            var traveltimetype = document.querySelector('input[name = "traveltimetype"]:checked').value;

            var service = new google.maps.DistanceMatrixService;
            service.getDistanceMatrix({
                origins: [origin],
                destinations: destinations,
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
                drivingOptions: {
                    departureTime: new Date(Date.now()), //Math.round(new Date().getTime()/1000),
                    trafficModel: traveltimetype
                }

            }, function(response, status) {
                if (status !== 'OK') {
                    alert('Error was: ' + status);
                } else {
                    var originList = response.originAddresses;
                    var destinationList = response.rows[0].elements;
                    var distance_google_tds = document.getElementsByClassName('distance_google');
                    var time_tds = document.getElementsByClassName('timetotravel');
                    var traffic_time_tds = document.getElementsByClassName('timetotravel_' + traveltimetype);
                    for(j in destinationList){
                        distance_google_tds[j].innerHTML = destinationList[j].distance.text;
                        time_tds[j].innerHTML = destinationList[j].duration.text;
                        traffic_time_tds[j].innerHTML = destinationList[j].duration_in_traffic.text;
                    }

                    var div_timestamps = document.getElementById('div_timestamps');
                    div_timestamps.innerHTML = "";

                }

            });

        }
    }
};


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

