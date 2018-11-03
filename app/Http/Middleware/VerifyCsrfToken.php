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
        //排出不需要csrf_token验证的路由
        '/member/create','/member/login','/address/store','/address/update','cart/store'
        ,'member/update','member/edit','order/store',
    ];
}
