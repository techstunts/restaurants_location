<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function get(Request $request)
    {
        return view('home');
    }

}
