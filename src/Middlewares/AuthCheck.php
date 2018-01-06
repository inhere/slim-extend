<?php

namespace slimExt\middlewares;

use Psr\Http\Message\ResponseInterface;
use inhere\exceptions\InvalidConfigException;
use Slim;
use slimExt\web\Request;
use slimExt\web\Response;

/**
 * Class AuthCheck  - 是否登录检查
 * @package slimExt\middlewares
 */
class AuthCheck
{
    /**
     * Auth middleware invokable class
     *
     * @param  Request $request PSR7 request
     * @param  Response $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return ResponseInterface
     * @throws InvalidConfigException
     * @throws \InvalidArgumentException
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        // if have been login
        if (Slim::$app->user->isLogin()) {
            return $next($request, $response);
        }

        return Slim::$app->user->loginRequired($request, $response);
    }
}