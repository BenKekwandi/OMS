<?php

namespace App\Enums;

enum ShipmentTypeEnum: string
{
    case OUTGOING = 'outgoing';
    case INCOMING = 'incoming';


    public function label(): string
    {
        return match ($this) {
            ShipmentTypeEnum::OUTGOING => 'outgoing',
            ShipmentTypeEnum::INCOMING => 'incoming',
        };
    }

}
