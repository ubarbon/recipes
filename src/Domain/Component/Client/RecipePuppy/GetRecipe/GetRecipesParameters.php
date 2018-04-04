<?php

namespace App\Domain\Component\Client\RecipePuppy\GetRecipe;

use App\Domain\Component\Client\RecipePuppy\PuppyRequestParameter;

/**
 * Class GetRecipesParameters
 * @package App\Domain\Component\Client\RecipePuppy
 * @author uriserbarbon@gmail.com
 */
class GetRecipesParameters extends PuppyRequestParameter
{
    /**
     * @var string|null
     */
    private $ingredients;

    /**
     * @var string|null
     */
    private $searchQuery;

    /**
     * GetRecipesParameters constructor.
     * @param int $page
     * @param null|string $ingredients
     * @param null|string $searchQuery
     */
    public function __construct(int $page, string $ingredients = null, string $searchQuery = null)
    {
        parent::__construct($page);

        $this->ingredients = $ingredients;
        $this->searchQuery = $searchQuery;
    }


    /**
     * @return string|null
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param string|null $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return string|null
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param string|null $searchQuery
     */
    public function setSearchQuery($searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $parameters = array(
            'p' => $this->getPage(),
            'i' => $this->getIngredients(),
            'q' => $this->getSearchQuery()
        );

        return http_build_query($parameters);
    }
}