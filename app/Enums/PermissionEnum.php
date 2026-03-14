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

    // Subscriptions Management
    const MANAGE_SUBSCRIPTIONS = "Manage subscriptions";
    const MANAGE_SUBSCRIPTIONS_ADD = "Manage subscriptions Add";
    const MANAGE_SUBSCRIPTIONS_UPDATE = "Manage subscriptions Update";
    const MANAGE_SUBSCRIPTIONS_DELETE = "Manage subscriptions Delete";
    const MANAGE_SUBSCRIPTIONS_VIEW = "Manage subscriptions View";

    // Agencies Management
    const MANAGE_AGENCIES = "Manage agencies";
    const MANAGE_AGENCIES_ADD = "Manage agencies Add";
    const MANAGE_AGENCIES_VIEW = "Manage agencies View";
    const MANAGE_AGENCIES_UPDATE = "Manage agencies Update";
    const MANAGE_AGENCIES_DELETE = "Manage agencies Delete";

    // User Subscriptions Management
    const MANAGE_USER_SUBSCRIPTIONS = "Manage user subscriptions";
    const MANAGE_USER_SUBSCRIPTIONS_VIEW = "Manage user subscriptions View";
    const MANAGE_USER_SUBSCRIPTIONS_UPDATE = "Manage user subscriptions Update";

    // Properties Management (Agent + Admin)
    const MANAGE_PROPERTIES = "Manage properties";
    const MANAGE_PROPERTIES_ADD = "Manage properties Add";
    const MANAGE_PROPERTIES_VIEW = "Manage properties View";
    const MANAGE_PROPERTIES_UPDATE = "Manage properties Update";
    const MANAGE_PROPERTIES_DELETE = "Manage properties Delete";
}
