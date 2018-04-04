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

        //Call Page 1
        $client->request('GET', '/recipe?page=1&search=omelet&ingredients=onions,garlic');
        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
        //save data
        $firstResultElementOnPage1 = $this->deserializeContent($client->getResponse()->getContent())['results'][0];

        //Call Page 2 with same filters
        $client->request('GET', '/recipe?page=2&search=omelet&ingredients=onions,garlic');
        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
        //save data
        $firstResultElementOnPage2 =  $this->deserializeContent($client->getResponse()->getContent())['results'][0];

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

        $client->request('GET', '/recipe?page=1&search=omelet');

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
    }

    public function testGetRecipesByPageAndSearchAndIngredients()
    {
        $client = static::createClient();

        $client->request('GET', '/recipe?page=1&search=omelet&ingredients=onions,garlic');

        $this->defaultValidation($client->getResponse());
        $this->contentValidation($client->getResponse());
    }

    private function defaultValidation(Response $response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'), printf('ContentType %s expected', $response->headers->get('Content-Type')));
    }

    private function contentValidation(Response $response, $deep = false)
    {
        $responseData = $this->deserializeContent($response->getContent());
        $this->assertArrayHasKey('results', $responseData, 'Not founded \'results\' key on response data');

        foreach ($responseData['results'] as $result) {
            $this->assertArrayHasKey('title', $result, 'Not founded \'title\' key on result item');
            $this->assertArrayHasKey('ingredients', $result, 'Not founded \'ingredients\' key on result item');
            $this->assertArrayHasKey('thumbnail', $result, 'Not founded \'thumbnail\' key on result item');
            if (!$deep) {
                break;
            }
        }
    }

    private function deserializeContent($content, $assoc = true)
    {
        return json_decode($content, $assoc);
    }
}