<?php
namespace App\Http\Controllers;

// use GuzzleHttp\Psr7\Request;

class LocationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLocationData()
    {
        $json = json_decode(file_get_contents('https://geolocation-db.com/json'));
        // $res = new Request('GET', 'https://geolocation-db.com/jsonp');
        return response()->json($json,200);
    }
}
