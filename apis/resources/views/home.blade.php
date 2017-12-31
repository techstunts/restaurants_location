<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid"></div>
        </nav>
        <div class="container-fluid text-center">
            <div class="row content">
                <div class="col-sm-4 sidenav">
                </div>
                <div class="col-sm-4 text-left">

                    <form class="form-horizontal" name="frm_restaurants" id="frm_restaurants" autocomplete="on">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="lat">Origin Latitude:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="lat" placeholder="Enter latitude" value="30.6909">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="lon">Origin Longitude:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="lon" placeholder="Enter longitude" value="76.7375">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="lon">Radius:</label>
                            <div class="col-sm-8">
                                <select name="rad" id="rad" class="form-control">
                                    <option value="1">1 km</option>
                                    <option value="5" selected="true">5 km</option>
                                    <option value="10">10 km</option>
                                    <option value="20">20 km</option>
                                    <option value="30">30 km</option>
                                    <option value="50">50 km</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input class="btn btn-default" type="button" name="submit" id="findBtn" value="Find restaurants">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-sm-4 sidenav">
                </div>
            </div>

            <div class="col-xs-12" style="height:50px;"></div>

            <div class="row content">
                <div class="col-sm-1 sidenav">
                </div>
                <div class="col-sm-10 text-left">
                    <div class="table-responsive ">
                        <table id="restaurants_list" class="table" style="display: none;">
                            <thead>
                            <th>Sl.</th>
                            <th>Restaurant</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Arial Distance by Algo</th>
                            <th>On Road Distance by Google API</th>
                            <th>Time to travel by Google API</th>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-sm-1 sidenav">
                </div>
        </div>
    </div>

    <script src="{!! url('js/restaurants.js') !!}" ></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_DISTANCE_API_KEY')}}&callback=initLocationFinder"></script>

</body>

</html>