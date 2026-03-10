<?php

namespace App\Enums;

/**
 * Permission definitions for the platform
 */
class PermissionEnum
{
    // User Management
    const USERS = "Users";
    const USERS_ADD = "Users Add";
    const USERS_DELETE = "Users Delete";
    const USERS_VIEW = "Users View";
    const USERS_UPDATE = "Users Update";

    // Settings Management
    const SETTING = "Setting";
    const SETTING_ADD = "Setting Add";
    const SETTING_DELETE = "Setting Delete";
    const SETTING_VIEW = "Setting View";
    const SETTING_UPDATE = "Setting Update";

    // Role Management
    const MANAGE_ROLES = "Manage roles";

    // Medical Specialties Management
    const MANAGE_SPECIALTIES = "Manage specialties";
    const MANAGE_SPECIALTIES_ADD = "Manage specialties Add";
    const MANAGE_SPECIALTIES_UPDATE = "Manage specialties Update";
    const MANAGE_SPECIALTIES_DELETE = "Manage specialties Delete";
    const MANAGE_SPECIALTIES_VIEW = "Manage specialties View";
}
