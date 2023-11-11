<?php

namespace app\core\middleware;

use app\core\http_context\Request;
use app\core\http_context\Response;

class BaseMiddleware
{
    protected $next_middleware;

    public function __construct()
    {
        $this->next_middleware = function (Request $request) {
            return new Response();
        };
    }

    public function add(IMiddleware $middleware)
    {
        $next = $this->next_middleware;

        $this->next_middleware = function (Request $request) use ($middleware, $next) {
            return $middleware->handle($request, $next);
        };
    }

    public function run(Request $request)
    {
        call_user_func($this->next_middleware, $request);
    }

    public function clear()
    {
        $this->next_middleware = function (Request $request) {
            return new Response();
        };
    }
}
