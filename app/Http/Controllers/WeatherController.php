<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zttp\Zttp;

class WeatherController extends Controller
{
    public function getWeather(Request $request){
        $apiKey = config('services.openweather.key');
        // $lat=request('lat');
        // $lng=request('lng');
        $location=$request->input('location');
        $reponse = Zttp::get("https://api.openweathermap.org/data/2.5/forecast?q=$location&appid=$apiKey&units=metric&cnt=40");
        return $reponse->json();
    }
}
