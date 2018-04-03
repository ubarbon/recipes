<?php

namespace App\Domain\Component\Client;

/**
 * Interface ClientInterface
 * @package App\Domain\Component\Client
 * @author uriserbarbon@gmail.com
 */
interface ClientInterface
{

    /**
     * @return string
     */
    public function getHost();

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function request($method, $uri = '', array $options = []);
}