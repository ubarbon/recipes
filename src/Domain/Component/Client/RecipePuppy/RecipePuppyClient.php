<?php

namespace App\Domain\Component\Client\RecipePuppy;

use App\Domain\Component\Client\AbstractClient;
use App\Domain\Component\Client\RecipePuppy\GetRecipe\GetRecipesParameters;
use App\Domain\Component\Client\RecipePuppy\GetRecipe\GetRecipesResponse;
use Psr\Log\LoggerInterface;

/**
 * Class RecipePuppyClient
 * @package App\Domain\Component\Client
 * @author uriserbarbon@gmail.com
 */
class RecipePuppyClient extends AbstractClient
{
    const GET_RECIPES_ENDPOINT = '/api/';

    /**
     * RecipePuppyClient constructor.
     * @param string $host
     * @param LoggerInterface $logger
     */
    public function __construct(string $host, LoggerInterface $logger)
    {
        parent::__construct($host, $logger);
    }


    /**
     * @param GetRecipesParameters|null $recipesParameters
     * @return GetRecipesResponse
     * @throws \Exception
     */
    public function getRecipes(GetRecipesParameters $recipesParameters)
    {
        $uri = $this->getHost() . self::GET_RECIPES_ENDPOINT . '?' . $recipesParameters;

        $response = $this->request('GET', $uri);

        $responseData = $this->deserializeContent($response);

        //verify mandatory parameters, in this case is 'results' key
        if (!isset($responseData['results'])) {
            throw new \Exception('It was not possible to obtain the mandatory key \'results\'');
        }

        return GetRecipesResponse::buildFromResponseData($responseData);
    }
}