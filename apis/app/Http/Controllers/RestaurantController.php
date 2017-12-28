<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RestaurantController extends Controller
{

    private  $RADIUS_OF_EARTH = 6371;

    public function get(Request $request)
    {
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $rad = $request->input('rad');; // radius of bounding circle in kilometers

        $R = $this->RADIUS_OF_EARTH;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($rad / $R);
        $minLat = $lat - rad2deg($rad / $R);
        $maxLon = $lon + rad2deg(asin($rad / $R) / cos(deg2rad($lat)));
        $minLon = $lon - rad2deg(asin($rad / $R) / cos(deg2rad($lat)));


        $sql = "Select id, name, lat, lon,
                   acos(sin(:lat)*sin(radians(lat)) + cos(:lat)*cos(radians(lat))*cos(radians(lon)-:lon)) * :R As D
            From (
                Select id, name, lat, lon
                From restaurants
                Where lat Between :minLat And :maxLat
                  And lon Between :minLon And :maxLon
            ) As FirstCut
            Where acos(sin(:lat)*sin(radians(lat)) + cos(:lat)*cos(radians(lat))*cos(radians(lon)-:lon)) * :R < :rad
            Order by D";

        $params = [
            'lat' => deg2rad($lat),
            'lon' => deg2rad($lon),
            'minLat' => $minLat,
            'minLon' => $minLon,
            'maxLat' => $maxLat,
            'maxLon' => $maxLon,
            'rad' => $rad,
            'R' => $R,
        ];

        $lat = deg2rad($lat);
        $lon = deg2rad($lon);

        $sql2 = "Select id, name, lat, lon,
                   acos(sin($lat)*sin(radians(lat)) + cos($lat)*cos(radians(lat))*cos(radians(lon)-$lon)) * $R As D
            From (
                Select id, name, lat, lon
                From restaurants
                Where lat Between $minLat And $maxLat
                  And lon Between $minLon And $maxLon
            ) As FirstCut
            Where acos(sin($lat)*sin(radians(lat)) + cos($lat)*cos(radians(lat))*cos(radians(lon)-$lon)) * $R < $rad
            Order by D";

        $restaurants2 = DB::select($sql2);


        //ToDo: Execute the query as a prepared statement
//        DB::enableQueryLog();
//        $restaurants = DB::select(DB::raw($sql, $params));
//        dd(DB::getQueryLog());

//        ToDo: Null response best practice
//        return response()->json(count($restaurants) ? $restaurants : null);
        return response()->json(count($restaurants2) ? $restaurants2 : null);
    }


}