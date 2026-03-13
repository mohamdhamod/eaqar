<?php

namespace App\Enums;

/**
 * Role definitions for the platform
 */
class RoleEnum
{
    const ADMIN = "Admin";
    const CLIENT = "Client";
    const AGENT = "Agent";

    const ALL = [
        self::ADMIN,
        self::CLIENT,
        self::AGENT,
    ];
}
