<?php
namespace THN\SDK\HTTP;

use PackageVersions\Versions;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class ApiHttpClient implements HttpClientInterface, UserAgentInterface
{
    private const AGENT_NAME = 'THN API SDK Http Client';

    private string $apiKey;

    private string $apiSecret;

    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var array
     */
    private array $userAgentFields;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->httpClient = HttpClient::create();

        $this->userAgentFields = [];

        $this->addAgentField('Client', get_class($this->httpClient));
        $this->addAgentField('Version', Versions::getVersion('symfony/http-client'));
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->updateOptions($options);
        return $this->httpClient->request($method, $url, $options);
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }

    private function updateOptions(array $options): void
    {
        $options['headers']['X-THN-API-Key'] = $this->apiKey;
        $options['headers']['X-THN-API-Secret'] = $this->apiSecret;
        $options['headers']['User-Agent'] = $this->getAgentString();
        $options['max_redirects'] = 0;
    }

    public function addAgentField(string $name, string $value): void
    {
        $this->userAgentFields[$name] = $value;
    }

    public function getAgentString(): string
    {
        $agent = self::AGENT_NAME;
        if (count($this->userAgentFields)>0)
        {
            $agent = $agent.' (';
            foreach ($this->userAgentFields as $key=>$value)
            {
                $agent = $agent.$key.':'.$value .' ';
            }
            $agent = trim($agent).')';
        }
        return $agent;
    }
}