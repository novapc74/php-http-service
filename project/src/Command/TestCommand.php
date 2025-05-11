<?php

namespace App\Command;

use Generator;
use App\Facades\DB;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

/**
 * bin/console app:test-command
 */
final class TestCommand
{
    private array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function execute(): int
    {
        $companyCollection = DB::table('company')->findAll();

        $client = new Client(['timeout' => 10.0,]);

        $pool = new Pool($client, $this->getRequests($companyCollection), [
            'concurrency' => 300,
            'fulfilled' => function (Response $response, $index) {
                #TODO что-то сделать с ответом, тут все ок...
                $data = json_decode($response->getBody()->getContents(), true)['value'] ?? [];
                echo 'Все прошло отлично! Есть ответ на запрос, получено тело.' . PHP_EOL;
            },
            'rejected' => function (RequestException $reason, $index) {
                #TODO тут что-то пошло не так, обработать, логировать...
                echo $reason->getMessage() . PHP_EOL;
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return 0;
    }

    public function getRequests(array $collection): Generator
    {
        foreach ($collection as $company) {
            $this->setAuth($company);
            $query = http_build_query([
                '$select' => implode(',', ['Ref_Key', 'Организация_Key', 'Контрагент_Key', 'Date'])
            ]);

            $uri = $company['odata_address'] . '/Document_ПоступлениеТоваровУслуг?' . $query;

            yield new Request('GET', $uri, $this->headers);;
        }
    }

    public function setAuth(array $data): void
    {
        $user = $data['odata_user'];
        $password = $data['odata_password'];
        $credentials = "$user:$password";

        $encodedCredentials = base64_encode($credentials);

        $this->headers['Authorization'] = 'Basic ' . $encodedCredentials;
    }
}
