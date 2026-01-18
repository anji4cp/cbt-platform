<?php

namespace App\Helpers;

class RoleHelper
{
    public static function isSuperAdmin()
    {
        return auth()->check() && auth()->user()->role === 'super_admin';
    }

    public static function isAdminSchool()
    {
        return auth()->check() && auth()->user()->role === 'admin_school';
    }
}
