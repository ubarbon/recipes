<?php

namespace App\Domain\Component\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use \GuzzleHttp\Psr7\Response;

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
     * @throws \Exception
     */
    public function request($method, $uri = '', array $options = [])
    {

        try {
            $response = $this->getHttpClient()->request($method, $uri, $options);
            $this->log($method, $uri, $response->getStatusCode(), $response->getHeaders(), (string)$response->getBody(), $options);
        } catch (\Exception $e) {
            //TODO please improve this
            $responseStatus = 500;
            $responseHeaders = array();
            $responseContent = $e->getMessage();

            if ($e instanceof ClientException) {
                $responseStatus = $e->getResponse()->getStatusCode();
                $responseHeaders = $e->getResponse()->getHeaders();
                $responseContent = (string)$e->getResponse()->getBody();
            }

            $this->log($method, $uri, $responseStatus, $responseHeaders, $responseContent, $options);

            throw $e;
        }


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
            default:
                throw new \InvalidArgumentException(printf('ContentType %s not supported', $contentType));
        }*/
    }

    /**
     * @return Client
     */
    private function getHttpClient(): Client
    {
        return new Client();
    }


    private function log($method, $uri, $responseStatus, $responseHeaders, $responseContent, array $options = []): void
    {
        $contextLog = [
            'request' => [
                'options' => $options
            ],
            'response' => [
                'status' => $responseStatus,
                'headers' => $responseHeaders,
                'content' => $responseContent,
            ]
        ];

        $logLevel = ($responseStatus >= 200 && $responseStatus < 300) ? LogLevel::DEBUG : LogLevel::CRITICAL;

        $this->getLogger()->log($logLevel, 'Request [' . $method . ']' . $uri, $contextLog);
    }
}