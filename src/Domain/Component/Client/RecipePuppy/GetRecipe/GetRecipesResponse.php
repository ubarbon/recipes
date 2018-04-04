<?php

namespace App\Domain\Component\Client\RecipePuppy\GetRecipe;

/**
 * Class GetRecipesResponse
 * @package App\Domain\Component\Client\RecipePuppy\GetRecipe
 * @author uriserbarbon@gmail.com
 */
class GetRecipesResponse
{

    /**
     * @var  array
     */
    private $results;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $href;

    /**
     * GetRecipesResponse constructor.
     * @param array $results
     * @param string $title
     * @param string $version
     * @param string $href
     */
    public function __construct(array $results, string $title, string $version, string $href)
    {
        $this->title = $title;
        $this->version = $version;
        $this->href = $href;
        $this->results = $results;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * @param array $responseData
     * @return GetRecipesResponse
     */
    public static function buildFromResponseData($responseData)
    {
        return new GetRecipesResponse(
            $responseData['results'],
            !isset($responseData['title']) ? null : $responseData['title'],
            !isset($responseData['version']) ? null : $responseData['version'],
            !isset($responseData['href']) ? null : $responseData['href']
        );
    }
}