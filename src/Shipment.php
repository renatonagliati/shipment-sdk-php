<?php

namespace MelhorEnvio;

use MelhorEnvio\Resources\Base;
use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Resources\Shipment\Cart;

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
}
