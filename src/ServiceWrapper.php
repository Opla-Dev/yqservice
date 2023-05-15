<?php

namespace YQService\oem;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise\Utils;
use YQService\oem\exceptions\ServiceServerException;
use YQService\oem\exceptions\YQException;

class ServiceWrapper
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @inheritDoc
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Request[] $requests
     * @return array
     * @throws YQException
     */
    public function queryBatch(array $requests): array
    {
        $responses = [];

        $client = new Client();
        $promises = [];

        foreach ($requests as $request) {

            $url = $this->config->getServiceUrl() . $request->getUrl();
            $langCode = $this->config->getAcceptLanguage();

            $options = [
                'headers' => [
                    'Accept-Language' => $langCode ?: 'en-US',
                ],
                'auth' => [
                    $this->config->getLogin(),
                    $this->config->getPassword()
                ],
                'json' => $request->getBody()
            ];
            $promises[] = $client->requestAsync($request->getMethod(), $url, $options);
        }

        try {
            $batchResponses = Utils::unwrap($promises);
        } catch (ClientException $exception) {
            throw new ServiceServerException(null, $exception->getMessage());
        } catch (Exception $error) {
            throw new ServiceServerException(null, $error->getMessage());
        }

        $index = 0;
        foreach ($requests as $key => $request) {
            try {
                $response = $batchResponses[$index++];
                $responseBody = $response->getBody()->getContents();

                $responses[$key] = $this->mapToObject($request, $responseBody, $this->config->isDebug());
            } catch (ClientException $exception) {
                throw new ServiceServerException($request, $exception->getMessage());
            } catch (Exception $error) {
                throw new ServiceServerException($request, $error->getMessage());
            }
        }

        return $responses;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws YQException
     */
    public function query(Request $request)
    {
        return $this->queryBatch([0 => $request])[0];
    }

    protected function mapToObject(Request $request, string $response, bool $debug)
    {
        $data = json_decode($response, true);
        if (!$data || @$data['error']['message']) {
            throw new ServiceServerException($request, $data['error']['message']);
        }

        $currentFilterState = @$data['currentFilterState'];

        $object = PropertyManager::mapData($request->getResponseClassName(), ['currentFilterState' => $currentFilterState], $data['data'], $debug);

        return $object;
    }

}