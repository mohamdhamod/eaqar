<?php

namespace App\Enums;


class ConfigurationsTypeEnum
{
    const OPERATION_TYPE = 'operation_type';
    const PROPERTY_TYPE = 'property_type';
    const CITY = 'city';
    const CURRENCY = 'currency';
    const ALL = [
        self::OPERATION_TYPE,
        self::PROPERTY_TYPE,
        self::CITY,
        self::CURRENCY,
    ];
}
