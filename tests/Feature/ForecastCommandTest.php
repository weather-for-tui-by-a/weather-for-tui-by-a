<?php

namespace Tests\Feature;


use App\Providers\WeatherDataProvider;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

class ForecastCommandTest extends TestCase
{
    public function test_get_cities_from_tui_api_and_check_they_have_lat_and_lon()
    {
        $cities = (new Response(WeatherDataProvider::getCities(new Http())));
        $response = TestResponse::fromBaseResponse($cities);
        $response->assertJsonStructure(
            [
                'latitude',
                'longitude',
            ],
            json_decode($cities->getContent(), true)[0]
        );
    }

    public function test_get_forecast_for_a_city()
    {
        $forecast = WeatherDataProvider::getForecastForACity(new Http(), '41.396', '2.175');

        $this->assertArrayHasKey('today', $forecast);
        $this->assertArrayHasKey('tomorrow', $forecast);
        $this->assertIsString($forecast['today']);
        $this->assertIsString($forecast['tomorrow']);
    }
}
