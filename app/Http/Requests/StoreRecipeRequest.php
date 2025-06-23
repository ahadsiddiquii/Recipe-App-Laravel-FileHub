<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'steps' => 'required|array|min:1',
            'steps.*.step' => 'required|string',
        ];

        if ($this->isMethod('put')) {
            $rules['title'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('recipes')->ignore($this->route('recipe')),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'ingredients.required' => 'The ingredients field is required.',
            'ingredients.array' => 'The ingredients field must be an array.',
            'ingredients.min' => 'At least one ingredient is required.',
            'ingredients.*.name.required' => 'Each ingredient must have a name.',
            'ingredients.*.quantity.required' => 'Each ingredient must have a quantity.',
            'ingredients.*.quantity.numeric' => 'The quantity must be a number.',
            'ingredients.*.quantity.min' => 'The quantity must be at least 0.01.',
            'steps.required' => 'The steps field is required.',
            'steps.array' => 'The steps field must be an array.',
            'steps.min' => 'At least one step is required.',
            'steps.*.step.required' => 'Each step must have a description.',
        ];
    }


    
}
