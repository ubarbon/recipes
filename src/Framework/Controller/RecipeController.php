<?php

namespace App\Framework\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecipeController
 * @package App\Framework\Controller
 * @author uriserbarbon@gmail.com
 */
class RecipeController extends Controller
{

    /**
     * @Route("/recipe", name="app_get_recipe")
     */
    public function recipe()
    {
        return $this->json([]);
    }
}