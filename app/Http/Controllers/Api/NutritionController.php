<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NutritionService;

class NutritionController extends Controller
{
    public function seed(NutritionService $ns)
    {
        $ingredients = [
            ['name' => 'Spinach', 'carbs' => 1.1, 'fat' => 0.1, 'protein' => 0.9],
            ['name' => 'Avocado', 'carbs' => 8.5,  'fat' => 14.7, 'protein' => 2],
        ];

        $results = [];
        foreach ($ingredients as $ing) {
            try {
                $results[] = $ns->add($ing['name'], $ing['carbs'], $ing['fat'], $ing['protein']);
            } catch (\Throwable $e) {
                $results[] = ['error' => $e->getMessage()];
            }
        }

        return response()->json(['added' => $results]);
    }
}
