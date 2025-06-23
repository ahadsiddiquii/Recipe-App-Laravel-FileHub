<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class NutritionService
{
    protected $endpoint;
    protected $auth;

    public function __construct()
    {
        $this->endpoint = env('APP_NUTRITION_API_ENDPOINT');
        $this->auth = [
            env('APP_NUTRITION_API_USERNAME'),
            env('APP_NUTRITION_API_PASSWORD'),
        ];
    }


     /**
     * Add a new ingredient to the database.
     *
     * @param string $name
     * @param float $carbs
     * @param float $fat
     * @param float $protein
     * @return array
     * @throws \Exception
     */
    public function add($name, $carbs, $fat, $protein)
    {
        $response = Http::withBasicAuth(...$this->auth)
                        ->asForm()
                        ->post($this->endpoint, compact('name','carbs','fat','protein'));

        if ($response->failed()) {
            throw new \Exception("API error: " . $response->status());
        }

        return $response->json();
    }

    /**
     * Fetch nutrition data for a given ingredient.
     *
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public function fetch($name)
    {
        $response = Http::withBasicAuth(...$this->auth)
                        ->get($this->endpoint, ['ingredient' => $name]);

        if ($response->failed()) {
            \Log::error('API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("API error: " . $response->status());
        }

        return $response->json();
    }
}
