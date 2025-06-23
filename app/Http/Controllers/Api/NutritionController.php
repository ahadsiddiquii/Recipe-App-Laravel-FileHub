<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NutritionService;

class NutritionController extends Controller
{
    public function seed(NutritionService $ns)
    {
        $ingredients = [
            ['name' => 'quinoa', 'carbs' => 21, 'fat' => 2, 'protein' => 4],
            ['name' => 'avocado', 'carbs' => 9,  'fat' => 15, 'protein' => 2],
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
