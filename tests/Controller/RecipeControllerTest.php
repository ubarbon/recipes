<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RecipeControllerTest
 * @package App\Tests\Controller
 * @author uriserbarbon@gmail.com
 */
class RecipeControllerTest extends WebTestCase
{

    public function testGetRecipes()
    {
        $client = static::createClient();

        $client->request('GET', '/recipe');

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse(), true);
    }

    public function testGetRecipesPagination()
    {
        $client = static::createClient();

        $client->request('GET', '/recipe?page=1');
        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());

        $search = 'Omelet';
        $ingredients = 'onions,garlic';

        $query = http_build_query(array(
            'page' => 1,
            'search' => $search,
            'ingredients' => $ingredients
        ));
        //Call Page 1
        $client->request('GET', '/recipe?' . $query);
        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
        //save data
        $firstResultElementOnPage1 = $this->deserializeContent($client->getResponse()->getContent())['recipes'][0];

        $query = http_build_query(array(
            'page' => 2,
            'search' => $search,
            'ingredients' => $ingredients
        ));
        //Call Page 2 with same filters
        $client->request('GET', '/recipe?' . $query);
        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
        //save data
        $firstResultElementOnPage2 = $this->deserializeContent($client->getResponse()->getContent())['recipes'][0];

        //We verify that the values ​​are at least different
        if ($firstResultElementOnPage1['title'] && $firstResultElementOnPage2['title']) {
            $this->assertNotEquals($firstResultElementOnPage1['title'], $firstResultElementOnPage2['title']);
        }

        if ($firstResultElementOnPage1['ingredients'] && $firstResultElementOnPage2['ingredients']) {
            $this->assertNotEquals($firstResultElementOnPage1['ingredients'], $firstResultElementOnPage2['ingredients']);
        }

        if ($firstResultElementOnPage1['thumbnail'] && $firstResultElementOnPage2['thumbnail']) {
            $this->assertNotEquals($firstResultElementOnPage1['thumbnail'], $firstResultElementOnPage2['thumbnail']);
        }

    }

    public function testGetRecipesByPageAndSearch()
    {
        $client = static::createClient();

        $search = 'Omelet';
        $query = http_build_query(array(
            'page' => 1,
            'search' => $search
        ));

        $client->request('GET', '/recipe?' . $query);

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());

        $responseData = $this->deserializeContent($client->getResponse()->getContent());

        foreach ($responseData['recipes'] as $recipe) {
            $this->searchValidation($search, $recipe);
        }
    }

    public function testGetRecipesByPageAndIngredients()
    {
        $client = static::createClient();

        $ingredients = 'onions,garlic';
        $query = http_build_query(array(
            'page' => 1,
            'ingredients' => $ingredients
        ));

        $client->request('GET', '/recipe?' . $query);

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());

        $responseData = $this->deserializeContent($client->getResponse()->getContent());

        foreach ($responseData['recipes'] as $recipe) {
            $this->ingredientsValidation(explode(',', $ingredients), $recipe);
        }
    }

    public function testGetRecipesByPageAndSearchAndIngredients()
    {
        $client = static::createClient();

        $search = 'Omelet';
        $ingredients = 'onions,garlic';

        $query = http_build_query(array(
            'page' => 1,
            'search' => $search,
            'ingredients' => $ingredients
        ));

        $client->request('GET', '/recipe?' . $query);

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());

        $responseData = $this->deserializeContent($client->getResponse()->getContent());

        foreach ($responseData['recipes'] as $recipe) {
            $this->searchValidation($search, $recipe);
            $this->ingredientsValidation(explode(',', $ingredients), $recipe);
        }
    }

    private function defaultValidation(Response $response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'), printf('ContentType %s expected', $response->headers->get('Content-Type')));
    }

    private function contentValidation(Response $response, $deep = false)
    {
        $responseData = $this->deserializeContent($response->getContent());
        $this->assertArrayHasKey('recipes', $responseData, 'Not founded \'recipes\' key on response data');

        foreach ($responseData['recipes'] as $recipe) {
            $this->assertArrayHasKey('title', $recipe, 'Not founded \'title\' key on recipe item');
            $this->assertArrayHasKey('ingredients', $recipe, 'Not founded \'ingredients\' key on recipe item');
            $this->assertArrayHasKey('thumbnail', $recipe, 'Not founded \'thumbnail\' key on recipe item');
            if (!$deep) {
                break;
            }
        }
    }

    private function deserializeContent($content, $assoc = true)
    {
        return json_decode($content, $assoc);
    }


    private function searchValidation($search, $recipe)
    {
        $this->assertTrue(strpos($recipe['title'], trim($search)) !== false);
    }

    private function ingredientsValidation($ingredients, $recipe)
    {
        $condition = false;
        foreach ($ingredients as $ingredient) {
            if (in_array($ingredient, explode(', ', trim($recipe['ingredients'])))) {
                $condition = true;
                break;
            }
        }

        $this->assertTrue($condition);
    }
}