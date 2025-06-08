<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class RajaOngkirServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('rajaongkir', function ($app) {
            return new class {
                protected $apiKey;
                protected $baseUrl;

                public function __construct()
                {
                    $this->apiKey = config('services.rajaongkir.key');
                    $this->baseUrl = config('services.rajaongkir.base_url');
                }

                public function provinces()
                {
                    return Http::withHeaders([
                        'key' => $this->apiKey
                    ])->get($this->baseUrl . 'province');
                }

                public function cities($provinceId = null)
                {
                    $params = [];
                    if ($provinceId) {
                        $params['province'] = $provinceId;
                    }

                    return Http::withHeaders([
                        'key' => $this->apiKey
                    ])->get($this->baseUrl . 'city', $params);
                }

                public function cost($origin, $destination, $weight, $courier)
                {
                    return Http::withHeaders([
                        'key' => $this->apiKey
                    ])->post($this->baseUrl . 'cost', [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier
                    ]);
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
