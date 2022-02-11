<?php

namespace MelhorEnvio\Resources\Shipment;

use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use MelhorEnvio\Exceptions\CancelException;
use MelhorEnvio\Exceptions\InvalidCancelPayloadException;
use MelhorEnvio\Exceptions\InvalidResourceException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Resource;

class Cancel
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

        $this->payload['order']['reason_id'] = '2';
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setOrder(string $order)
    {
        if (! $this->isValidOrder($order)) {
            throw new InvalidVolumeException('order');
        }

        $this->payload['order']['id'] = $order;
    }

    public function isValidOrder(string $order): bool
    {
        return $order != null && $order != '';
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setDescription(string $description)
    {
        if ($description == '') {
            throw new InvalidVolumeException('description');
        }

        $this->payload['order']['description'] = $description;
    }

    protected function validatePayload(): void
    {
        if (empty($this->payload['order']['id'])) {
            throw new InvalidCancelPayloadException('There is no defined order.');
        }

        if ( !isset($this->payload['order']['description']) || $this->payload['order']['description'] == '') {
            throw new InvalidCancelPayloadException('There is no defined description.');
        }
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @throws InvalidCancelPayloadException|CancelException
     */
    public function process()
    {
        $this->validatePayload();

        try {
            $response = $this->resource->getHttp()->post('me/shipment/cancel', [
                'json' => $this->payload,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $exception) {
            throw new CancelException($exception);
        }
    }

    public function __toString():  ?string
    {
        return json_encode($this->getPayload());
    }
}
