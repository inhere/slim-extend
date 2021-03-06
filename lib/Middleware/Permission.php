<?php

namespace SlimExt\Middleware;

use Psr\Http\Message\ResponseInterface;
use SlimExt\Web\Request;
use SlimExt\Web\Response;

/**
 * Class Permission
 * @package SlimExt\Middleware
 */
class Permission
{
    /**
     * Permission middleware invokable class
     *
     * @param  Request $request PSR7 request
     * @param  Response $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return ResponseInterface
     * @throws \Inhere\Exceptions\NotFoundException
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $passed = $this->doCheck($request);

        // if passed == true, go on ...
        if ($passed) {
            return $next($request, $response);
        }

        $msg = \Slim::$app->language->tl('http403');

        // when is xhr
        if ($request->isXhr()) {
            return $response->withJson(403, $msg)->withStatus(403);
        }

        return $response->withStatus(403)->write($msg);
    }

    protected function doCheck(Request $request)
    {
        // some logic ... ...

        return true;
    }
}
