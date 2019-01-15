<?php

namespace Skulditskiy\LambdaPhp\Dummy;

use GuzzleHttp\ClientInterface;
use Skulditskiy\LambdaPhp\HandlerInterface;

class Handler implements HandlerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        /**
         * something usefull with request, like:
         *  $apiResponse = $this->client->request('GET', 'https://reqres.in/api/users/2');
         *  $result = json_decode($apiResponse->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
         */
        return [
            'here your data' => $data,
            'some additional data' => microtime(true),
        ];
    }
}

