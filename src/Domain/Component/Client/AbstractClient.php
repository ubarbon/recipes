<?php

namespace App\Domain\Component\Client;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use \Symfony\Component\BrowserKit\Response;

/**
 * Class AbstractClient
 * @package App\Domain\Component\Client
 * @author uriserbarbon@gmail.com
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractClient constructor.
     * @param LoggerInterface $logger
     * @param string $host
     */
    public function __construct(string $host, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->host = $host;
    }


    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return Response
     */
    public function request($method, $uri = '', array $options = [])
    {
        $response = $this->getHttpClient()->request($method, $uri, $options);

        $contextLog = [
            'request' => [
                'options' => $options
            ],
            'response' => [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'content' => (string)$response->getBody(),
            ]
        ];

        $logLevel = ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) ? 'DEBUG' : 'CRITICAL';

        $this->getLogger()->log($logLevel, 'Request [' . $method . ']' . $uri, $contextLog);

        return $response;
    }

    /**
     * @param Response $response
     * @return array
     */
    public function deserializeContent(Response $response)
    {
        return json_decode($response->getBody(), true);

        //TODO RecipePuppy Api return 'text/javascript' as content type instead of 'application/json',please change to this when the api returns the content type correctly
        /*$contentType = $response->getHeader('Content-Type');
        switch (true) {
            case preg_match('/json/', $contentType):
                return json_decode($response->getBody(), true);
                break;
            default:
                throw new \InvalidArgumentException(printf('ContentType % not supported', $contentType));
        }*/
    }

    /**
     * @return Client
     */
    private function getHttpClient(): Client
    {
        return new Client();
    }


}