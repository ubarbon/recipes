<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}