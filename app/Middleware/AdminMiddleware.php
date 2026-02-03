<?php

namespace App\Middleware;

use App\Helpers\AuthHelper;

/**
 * AdminMiddleware
 * 
 * Middleware for protecting admin routes
 */
class AdminMiddleware
{
    public static function handle()
    {
        AuthHelper::requireAdmin();
    }
}
