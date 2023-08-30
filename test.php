<?php

// creating the array and reading the data before we start
$recipes = [];
$db = 'recipes.json';
if(file_exists($db)) 
{
  $recipes = json_decode(file_get_contents($db), true);
}

while (true) 
{   
  echo PHP_EOL . "choose an option" . PHP_EOL;
  echo ("1. Search for an recipe") . PHP_EOL;
  echo ("2. Add Recipe") . PHP_EOL;
  echo ("3. Remove recipe") . PHP_EOL;
  echo ("4. List all recipes") . PHP_EOL;
  echo ("5. exit") . PHP_EOL . PHP_EOL;
  $line = (int)readline("choose an option: ");

  switch($line) 
  {
    case 1:   
      echo PHP_EOL . 'input the ingredients for the search' . PHP_EOL;
      $input = trim(readline());
      $input = strtolower($input);
      search($input, $recipes);
      break;
    case 2:
      add($recipes);      
      break;
    case 3:
      remove($recipes);
      break;
    case 4:
      echo PHP_EOL . 'All recipes: ' . PHP_EOL;    
      foreach ($recipes as $recipe) 
      {
        echo $recipe['name'] . PHP_EOL;
      }
      break;
    case 5: 
      file_put_contents($db, json_encode($recipes, JSON_PRETTY_PRINT));
      exit;
    default:        
      echo ("unknown choice. Try again." . PHP_EOL);
  }
}  

function search($input, $recipes)
{
  $ingredients = preg_split('/,\s*|\s*,\s*/', $input);

  $recipeMatch = [];

  foreach ($recipes as $recipe) 
  {
    $recipeIngredients = $recipe['ingredients'];
    $diff = array_diff($ingredients, $recipeIngredients);

    // checks if there are any difference's from ingredients and search
    if (count($diff) === 0) 
    {
      $recipeMatch[] = $recipe;
    }
  }

  if (!empty($recipeMatch)) 
  {
    echo PHP_EOL . "Matching recipes:" . PHP_EOL;
    foreach ($recipeMatch as $recipe) 
    {
      echo $recipe['name'] . PHP_EOL;            
    }
  } 
  else 
  {
    echo "No matching recipes found." . PHP_EOL;      
  }
}

// the & before the variable makes it possible edit the array, since it references it
function add(&$recipes)
{  
  echo "Add recipe name: ";
  $name = trim(readline());
  $name = strtolower($name);

  echo "Add ingredients (comma separated): ";
  $input = trim(readline());
  $input = strtolower($input);
  // $ingredients = explode(',', $input);
  $ingredients = preg_split('/,\s*|\s*,\s*/', $input);

  $recipes[] = ['name' => $name, 'ingredients' => $ingredients];  
}

function remove(&$recipes)
{
  allRecipes($recipes);
  echo PHP_EOL . "What recipe to remove: ";
  $name = trim(readline());
  $name = strtolower($name);

  foreach ($recipes as $key => $recipe) 
  {
    if($recipe['name'] === $name)
    {
      unset($recipes[$key]);
      break;
    }
  }

  echo "Your recipe was removed." . PHP_EOL;
}

function allRecipes(&$recipes)
{
  echo 'All recipes: ' . PHP_EOL;    
    foreach ($recipes as $recipe) 
    {
      echo $recipe['name'] . PHP_EOL;        
    }
}

?>
