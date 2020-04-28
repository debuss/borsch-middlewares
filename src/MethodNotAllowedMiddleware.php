<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Borsch\Router\RouteResult;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MethodNotAllowedMiddleware
 *
 * Generates a 405 Method Not Allowed response.
 *
 * @package Borsch\Middleware
 */
class MethodNotAllowedMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route_result = $request->getAttribute(RouteResult::class);
        if (!$route_result || !$route_result->isMethodFailure()) {
            return $handler->handle($request);
        }

        /** @var ResponseFactoryInterface $response_factory */
        $response_factory = $request->getAttribute(ResponseFactoryInterface::class);

        return $response_factory->createResponse()
            ->withStatus(405, 'Method Not Allowed')
            ->withHeader(
                'Allow',
                implode(',', $route_result->getAllowedMethods())
            );
    }
}
