<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/login',
        'api/register',
        'api/index',
        'face',
        'api/user/preferences',
        'api/instagram/oauth',
        'photos/upload',
        'api/user/password',
        'api/user/photo',
        '/user/avatar',
        'photos/remove',
        'api/user/avatar/get',
        'api/product'
    ];
}
