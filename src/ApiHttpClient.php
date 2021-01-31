<?php
namespace THN\SDK\HTTP;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class ApiHttpClient implements HttpClientInterface
{
    private string $apiKey;

    private string $apiSecret;

    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->httpClient = HttpClient::create();
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, $options);
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }

}