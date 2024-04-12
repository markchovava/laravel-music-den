<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'http://127.0.0.1:8000/register',
        'http://127.0.0.1:8000/login',
        'http://127.0.0.1:8000/login-by-gmail',
        'http://127.0.0.1:8000/register-by-gmail',
        'http://127.0.0.1:8000/user',
        'http://127.0.0.1:8000/user/*',
    ];
}
