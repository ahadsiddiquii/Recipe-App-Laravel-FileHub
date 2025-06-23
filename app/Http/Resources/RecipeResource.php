<?php
class RecipeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'nutrition'     => [
                'carbs'   => $this->total_carbs,
                'fat'     => $this->total_fat,
                'protein' => $this->total_protein,
            ],
            'ingredients'   => $this->ingredients,
            'steps'         => $this->steps->sortBy('step_number')->values(),
            'created_at'    => $this->created_at,
        ];
    }
}
