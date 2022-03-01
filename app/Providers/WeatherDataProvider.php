<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class WeatherDataProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public static function getCities(Http $cities)
    {
        return $cities::get('https://api.musement.com/api/v3/cities');
    }

    public static function getForecastForACity(Http $forecast, float $latitude, float $longitude)
    {
        $key = env('WEATHER_API_KEY');
        $forecast = $forecast::get("https://api.weatherapi.com/v1/forecast.json?key={$key}&q={$latitude},{$longitude}&days=2&aqi=no&alerts=no");

        return [
            'today' => $forecast['forecast']['forecastday'][0]['day']['condition']['text'],
            'tomorrow' => $forecast['forecast']['forecastday'][1]['day']['condition']['text']
        ];
    }
}
