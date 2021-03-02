<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ApiController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', '-1');
    }

    public function storePrevilegeCard(Request $request)
    {
        return $request->all();

        //return response()->json($article, 201);
    }
}
