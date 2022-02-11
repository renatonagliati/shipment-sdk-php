<?php

namespace MelhorEnvio;

use MelhorEnvio\Resources\Base;
use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Resources\Shipment\Cart;
use MelhorEnvio\Resources\Shipment\Checkout;
use MelhorEnvio\Resources\Shipment\Generate;
use MelhorEnvio\Resources\Shipment\Orders;
use MelhorEnvio\Resources\Shipment\ToPrint;

class Shipment extends Base
{
    public function calculator(): Calculator
    {
        return new Calculator($this);
    }

    public function cart(): Cart
    {
        return new Cart($this);
    }

    public function checkout(): Checkout
    {
        return new Checkout($this);
    }

    public function generate(): Generate
    {
        return new Generate($this);
    }

    public function toPrint(): ToPrint
    {
        return new ToPrint($this);
    }

    public function orders(): Orders
    {
        return new Orders($this);
    }
}
