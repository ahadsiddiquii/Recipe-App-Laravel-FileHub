<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Services\NutritionService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {   try{
        $recipes = Recipe::with(['ingredients', 'steps'])->get();

        // Calculate total nutritional values
        $totalNutrition = [
            'carbs' => $recipes->sum(function ($recipe) {
                return $recipe->ingredients->sum(function ($ing) {
                    return $ing->carbs * $ing->pivot->quantity;
                });
            }),
            'fat' => $recipes->sum(function ($recipe) {
                return $recipe->ingredients->sum(function ($ing) {
                    return $ing->fat * $ing->pivot->quantity;
                });
            }),
            'protein' => $recipes->sum(function ($recipe) {
                return $recipe->ingredients->sum(function ($ing) {
                    return $ing->protein * $ing->pivot->quantity;
                });
            }),
        ];


        // Return the response with additional nutrition data
        return response()->json([
            'status' => 'success',
            'data' => $recipes,
            'nutrition' => $totalNutrition,
            'message' => 'Recipes retrieved successfully',
            'errors' => null,
        ], 200);
         }catch (\Exception $e) {
           return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Failed to retrieve recipe',
                    'errors' => ['exception' => $e->getMessage()]
                ], 500);
        }
    }

    public function store(StoreRecipeRequest $req, NutritionService $ns)
    {   
        try{
            // This line creates a new Recipe record in the recipes table using the title provided in the request.
            $r = Recipe::create($req->only('title'));
            // Initializing Nutrition Data Default
            $nut = ['carbs'=>0,'fat'=>0,'protein'=>0];
            // Processing Ingredients
            foreach ($req->ingredients as $ing) {
                try {
                    // Fetching data from nutrition API
                    $nutrition = $ns->fetch($ing['name']);
                } catch (\Exception $e) {
                    // Giving error if nutrition item does not exist
                    return response()
                        ->json(['error' => "Ingredient '{$ing['name']}' not found in Nutrition API"], 422);
                }
                
                $model = Ingredient::firstOrCreate(
                    ['name'=>$ing['name']],
                    $nutrition,
                );
                $r->ingredients()->attach($model->id, ['quantity'=>$ing['quantity']]);
                // These lines accumulate the total nutritional values for the recipe by iterating over each ingredient.
                $nut['carbs'] += $model->carbs * $ing['quantity'];
                $nut['fat']   += $model->fat   * $ing['quantity'];
                $nut['protein'] += $model->protein * $ing['quantity'];
            }
            // Processing Steps
            foreach ($req->steps as $i => $step) {
            $r->steps()->create([
                'step_number' => $i + 1,
                'description' => $step['step'] ?? 'No step provided',
            ]);
            }
            return response()->json(['status' => 'success',  'message' => 'Recipe stored successfully', 'data'=>$r->load('ingredients','steps'),'nutrition'=>$nut],201);
        }catch (\Exception $e) {
           return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Failed to retrieve recipe',
                    'errors' => ['exception' => $e->getMessage()]
                ], 500);
        }
    }

    public function update(StoreRecipeRequest $req, Recipe $recipe, NutritionService $ns)
    {
        try {
            // Update the recipe title
            $recipe->update($req->only('title'));

            // Reset nutrition totals
            $nut = ['carbs' => 0, 'fat' => 0, 'protein' => 0];

            // Detach old ingredients and delete old steps
            $recipe->ingredients()->detach();
            $recipe->steps()->delete();

            // Process new ingredients
            foreach ($req->ingredients as $ing) {
                try {
                    $nutrition = $ns->fetch($ing['name']);
                } catch (\Exception $e) {
                    return response()->json(['error' => "Ingredient '{$ing['name']}' not found in Nutrition API"], 422);
                }

                $model = Ingredient::firstOrCreate(
                    ['name' => $ing['name']],
                    $nutrition
                );

                $recipe->ingredients()->attach($model->id, ['quantity' => $ing['quantity']]);

                $nut['carbs'] += $model->carbs * $ing['quantity'];
                $nut['fat'] += $model->fat * $ing['quantity'];
                $nut['protein'] += $model->protein * $ing['quantity'];
            }

            // Process new steps
            foreach ($req->steps as $i => $step) {
                $recipe->steps()->create([
                    'step_number' => $i + 1,
                    'description' => $step['step'] ?? 'No step provided',
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recipe updated successfully',
                'data' => $recipe->load('ingredients', 'steps'),
                'nutrition' => $nut
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Failed to update recipe',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function show(Recipe $recipe)
    {
        try {
            $recipe->load('ingredients','steps');
            $nut = ['carbs'=>0,'fat'=>0,'protein'=>0];
            foreach ($recipe->ingredients as $ing) {
                $nut['carbs'] += $ing->carbs * $ing->pivot->quantity;
                $nut['fat']   += $ing->fat   * $ing->pivot->quantity;
                $nut['protein'] += $ing->protein * $ing->pivot->quantity;
            }
            return response()->json([
                'status' => 'success',
                'data' => $recipe,
                'message' => 'Recipe retrieved successfully',
                'nutrition' => $nut,
                'errors' => null
            ], 200);
            } 
          
            catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to retrieve recipe',
                    'errors' => ['exception' => $e->getMessage()]
                ], 500);
             }
    }

    public function destroy(Recipe $recipe)
    {
        try {
            $recipe->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Recipe deleted successfully',
            ], 200);
        } 
   
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete recipe',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
