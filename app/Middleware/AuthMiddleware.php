<?php

namespace App\Middleware;

use App\Helpers\AuthHelper;

/**
 * AuthMiddleware
 * 
 * Middleware for protecting routes that require authentication
 */
class AuthMiddleware
{
    public static function handle()
    {
        AuthHelper::requireAuth();
    }
}
