<?php

namespace App\Data\Recipe;

/**
 * Class GetRecipesResponse
 * @package App\Data\Recipe
 * @author uriserbarbon@gmail.com
 */
class GetRecipesResponse
{
    /**
     * @var array
     */
    private $results;

    /**
     * GetRecipesResponse constructor.
     * @param array $results
     */
    public function __construct(array $results)
    {
        $this->results = $results;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $response = array('results' => array());

        foreach ($this->getResults() as $result) {
            $response['results'][] = array(
                'title' => $result['title'],
                'ingredients' => $result['ingredients'],
                'thumbnail' => $result['thumbnail']
            );
        }

        return $response;
    }

    /**
     * @return array
     */
    private function getResults(): array
    {
        return $this->results;
    }
}