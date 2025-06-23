**Endpoints**

**Nutrition API**
1. Add 2 ingredients in the FileHub ingredients data (Wrapping my api over this URL: https://interview.workcentrix.de/ingredients.php)
URL: http://127.0.0.1:8000/api/nutrition/seed
Method: POST
Description: This method will add 2 ingredients **spinach** and **avocado**
    {
        "name": "Spinach",
        "carbs": "1.1",
        "fat": "0.1",
        "protein": "0.9"
    },
    {
        "name": "Avocado",
        "carbs": "8.5",
        "fat": "14.7",
        "protein": "2.0"
    }

**Recipe API**
1. Create Recipe
URL: http://127.0.0.1:8000/api/recipes
Method: POST
Description: Create a new recipe with title, ingredients, and preparation steps.
json
{
  "title": "Apple Quinoa Salad 22",
  "ingredients": [
    {"name": "Quinoa", "quantity": 1},
    {"name": "Apple", "quantity": 0.5}
  ],
  "steps": [
    {"step": "Cook quinoa 22."},
    {"step": "Cook quinoa 23."}
  ]
}

2. Update Recipe
URL:  http://127.0.0.1:8000/api/recipes/{id}
Method: PUT
Description: Update an existing recipe's title, ingredients, or steps.
Request Body:
{
  "title": "Updated Apple Quinoa Salad",
  "ingredients": [
    {"name": "Quinoa", "quantity": 1.5},
    {"name": "Apple", "quantity": 0.6}
  ],
  "steps": [
    {"step": "Cook quinoa 30."},
    {"step": "Cook quinoa 35."}
  ]
}

3. Get All Recipes
URL:  http://127.0.0.1:8000/api/recipes
Method: GET
Description: Fetches all the added recipes in the table.

4. Get Single Recipe
URL:  http://127.0.0.1:8000/api/recipes/{id}
Method: GET
Description: Fetches recipe of the id given.

5. Delete Recipe
URL:  http://127.0.0.1:8000/api/recipes/{id}
Method: DELETE
Description: Delete recipe of the id given.
