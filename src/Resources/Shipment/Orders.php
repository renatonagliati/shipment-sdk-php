<?php

namespace MelhorEnvio\Resources\Shipment;

use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use MelhorEnvio\Exceptions\OrdersException;
use MelhorEnvio\Exceptions\InvalidOrdersPayloadException;
use MelhorEnvio\Exceptions\InvalidResourceException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Resource;

class Orders
{
    protected string $order = '';

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
    public function setOrder(string $order)
    {
        if (! $this->isValidOrder($order)) {
            throw new InvalidVolumeException('order');
        }

        $this->order = $order;
    }

    public function isValidOrder(string $order): bool
    {
        return $order != null && $order != '';
    }

    protected function validatePayload(): void
    {
        if (! $this->isValidOrder($this->order)) {
            throw new InvalidOrdersPayloadException('There is no defined order.');
        }
    }


    /**
     * @throws InvalidOrdersPayloadException|OrdersException
     */
    public function process()
    {
        $this->validatePayload();

        try {
            $response = $this->resource->getHttp()->get('me/orders/' . $this->order);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $exception) {
            throw new OrdersException($exception);
        }
    }
}
