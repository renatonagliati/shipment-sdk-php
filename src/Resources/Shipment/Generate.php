<?php

namespace MelhorEnvio\Resources\Shipment;

use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use MelhorEnvio\Exceptions\GenerateException;
use MelhorEnvio\Exceptions\InvalidGeneratePayloadException;
use MelhorEnvio\Exceptions\InvalidResourceException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Resource;

class Generate
{
    protected array $payload = [];

    protected Resource $resource;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Resource $resource)
    {
        if (! $resource instanceof Resource) {
            throw new InvalidResourceException;
        }

        $this->resource = $resource;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addOrders(string $orders)
    {
        $orders = is_array($orders) ? $orders : func_get_args();

        foreach ($orders as $order) {
            $this->addOrder($order);
        }
    }

    /**
     * @throws InvalidVolumeException
     */
    public function addOrder(string $order)
    {
        if (! $this->isValidOrder($order)) {
            throw new InvalidVolumeException('order');
        }

        $this->payload['orders'][] = $order;
    }

    public function isValidOrder(string $order): bool
    {
        return $order != null && $order != '';
    }

    protected function validatePayload(): void
    {
        if (empty($this->payload['orders'])) {
            throw new InvalidGeneratePayloadException('There are no defined orders.');
        }
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @throws InvalidGeneratePayloadException|GenerateException
     */
    public function process()
    {
        $this->validatePayload();

        try {
            $response = $this->resource->getHttp()->post('me/shipment/generate', [
                'json' => $this->payload,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $exception) {
            throw new GenerateException($exception);
        }
    }

    public function __toString():  ?string
    {
        return json_encode($this->getPayload());
    }
}
