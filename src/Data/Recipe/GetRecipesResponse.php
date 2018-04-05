<?php

namespace App\Data\Recipe;

use App\Domain\Component\Client\RecipePuppy\GetRecipe\GetRecipesResponse as RecipesResponse;

/**
 * Class GetRecipesResponse
 * @package App\Data\Recipe
 * @author uriserbarbon@gmail.com
 */
class GetRecipesResponse
{
    /**
     * @var RecipesResponse
     */
    private $recipesResponse;

    /**
     * GetRecipesResponse constructor.
     * @param RecipesResponse $recipeResponse
     */
    public function __construct(RecipesResponse $recipeResponse)
    {
        $this->recipesResponse = $recipeResponse;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $response = array('recipes' => array());

        foreach ($this->getRecipeResponse()->getResults() as $recipe) {
            $response['recipes'][] = array(
                'title' => $recipe['title'],
                'ingredients' => $recipe['ingredients'],
                'thumbnail' => $recipe['thumbnail']
            );
        }

        return $response;
    }

    /**
     * @return RecipesResponse
     */
    private function getRecipeResponse(): RecipesResponse
    {
        return $this->recipesResponse;
    }
}