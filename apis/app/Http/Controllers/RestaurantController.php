<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RestaurantController extends Controller{

    public function get(Request $request){
        $lat = $request->input('lat');
        $lon = $request->input('lon');

        $restaurants = Restaurant::where(['lat'=> $lat, 'lon' => $lon])->get();

        return response()->json(count($restaurants) ? $restaurants : null);
    }

}