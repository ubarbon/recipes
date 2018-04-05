<?php

namespace App\Framework\Controller;

use App\Data\Recipe\GetRecipesResponse;
use App\Domain\Component\Client\RecipePuppy\GetRecipe\GetRecipesParameters;
use App\Domain\Component\Client\RecipePuppy\RecipePuppyClient;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Request $request
     * @param RecipePuppyClient $recipePuppyClient
     * @return JsonResponse | Response
     * @throws \Exception
     */
    public function getRecipes(Request $request, RecipePuppyClient $recipePuppyClient)
    {
        //TODO This manipulation of exception in a controller is not very good practice, so please develop events and event listeners, source from http://symfony.com/doc/current/event_dispatcher.html
        try {
            $recipesParameters = new GetRecipesParameters($request->get('page', 1), $request->get('ingredients'), $request->get('search'));

            $responseData = new GetRecipesResponse($recipePuppyClient->getRecipes($recipesParameters));

            return $this->json($responseData->getResponse());

        } catch (\Exception $e) {

            if ($e instanceof ClientException) {
                $status = 503;
                $userMsg = 'An error has occurred, please try later';//TODO please, in the future use the translation service of symfony  for it, Ex: TranslatorInterface, source from https://symfony.com/doc/current/translation.html
                $devMsg = 'Service Unavailable, problem with recipe puppy client';

                return $this->json(array('errors' => array(
                    array(
                        'status' => $status,
                        'userMsg' => $userMsg,
                        'devMsg' => $devMsg
                    )
                )), $status);
            }

            throw $e;
        }
    }
}