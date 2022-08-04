<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
   
    public function controllerMethod(){
        return response() ->json([
            'msg' => 'This is the message'
        ]);
    }

    public function test(){
       return response()->json([
           'msg' => 'Some error occured'
       ],422);
    }
}
