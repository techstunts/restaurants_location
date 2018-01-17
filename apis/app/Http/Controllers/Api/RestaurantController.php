<?php

namespace App\Http\Controllers\Api;

use App\Http\Mappers\RestaurantMapper;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;


class RestaurantController extends Controller
{

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'numeric|min:-90|max:90',
            'lon' => 'numeric|min:-180|max:180',
            'rad' => 'numeric|min:0|max:2000',
            'count' => 'numeric|min:1|max:250'
        ]);
        if ($validator->fails()) {
            $messages = '';
            foreach ($validator->messages()->getMessages() as $field_name => $m)
            {
                $messages = $m[0] . "\n"; // messages are retrieved (publicly)
            }
            $error = [
                'status' => 'NOTOK',
                'message' => $messages
            ];
            return response()->json($error, 400);
        }

        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $rad = $request->input('rad'); // radius of bounding circle in kilometers
        $count = $request->has('count') ? $request->input('count') : 20;

        $restaurantMapper = new RestaurantMapper();
        $results = $restaurantMapper->getRestaurantsByLatLon($lat, $lon, $count, $rad);

        if(count($results)){
            $response = [
                'status' => 'OK',
                'results' => $results
            ];
            $status_code = 200;
        }
        else{
            $response = [
                'status' => 'NOTOK',
                'message' => 'NO DATA FOUND'
            ];
            $status_code = 404;
        }
        return response()->json($response, $status_code);
    }


}