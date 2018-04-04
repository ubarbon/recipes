<?php

namespace App\Domain\Component\Client\RecipePuppy;

/**
 * Class PuppyRequestParameter
 * @package App\Domain\Component\Client\RecipePuppy
 * @author uriserbarbon@gmail.com
 */
abstract class PuppyRequestParameter
{
    /**
     * @var int
     */
    protected $page;

    /**
     * PuppyRequestParameter constructor.
     * @param int $page
     */
    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }


    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }


    /**
     * @return string
     */
    public abstract function __toString();
}