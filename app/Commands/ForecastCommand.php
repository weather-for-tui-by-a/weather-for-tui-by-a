<?php

namespace App\Commands;

use App\Providers\WeatherDataProvider;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Http;

class ForecastCommand extends Command
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cities:forecast_for_next_two_days';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Displays forecast for each city in the TUI API for next two days';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Http $cities, Http $forecast)
    {
        try {
            $cities = WeatherDataProvider::getCities($cities);


            $cities->collect()->each(function ($city) use ($forecast) {
                try {
                    $forecast = WeatherDataProvider::getForecastForACity($forecast, $city['latitude'], $city['longitude']);

                    $this->printToStdout($city['name'], $forecast['today'], $forecast['tomorrow']);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            });
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function printToStdout(string $cityName, string $forecastForToday, string $forecastForTomorrow)
    {
        $this->info("Processed city {$cityName} | Forecast for today: {$forecastForToday} - Forecast fot tomorrow: {$forecastForTomorrow}");
    }
}
