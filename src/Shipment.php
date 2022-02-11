<?php

namespace MelhorEnvio;

use MelhorEnvio\Resources\Base;
use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Resources\Shipment\Cart;
use MelhorEnvio\Resources\Shipment\Checkout;
use MelhorEnvio\Resources\Shipment\Generate;

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
}
