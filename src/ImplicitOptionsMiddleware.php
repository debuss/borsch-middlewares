<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Borsch\Router\RouteResult;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ImplicitOptionsMiddleware
 *
 * Generates a response with status code 200 and an Allow header.
 *
 * @package Borsch\Middleware
 */
class ImplicitOptionsMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) != 'OPTIONS') {
            return $handler->handle($request);
        }

        $result = $request->getAttribute(RouteResult::class);
        if (!$result) {
            return $handler->handle($request);
        }

        if ($result->isFailure() && !$result->isMethodFailure()) {
            return $handler->handle($request);
        }

        if ($result->getMatchedRoute()) {
            return $handler->handle($request);
        }

        $response = new Response();

        return $response->withHeader(
            'Allow',
            implode(', ', $result->getAllowedMethods())
        );
    }
}
