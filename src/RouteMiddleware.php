<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Borsch\Router\RouteResult;
use Borsch\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouteMiddleware
 * 
 * Uses the ServerRequestInterface to match routes from the router.
 * The router is in the request attributes (RouterInterface::class).
 * Set the RouteResult in the request attributes (RouteResult::class).
 * 
 * @package Borsch\Middleware
 */
class RouteMiddleware implements MiddlewareInterface
{

    /** @var RouterInterface */
    protected $router;

    /**
     * RouteMiddleware constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(RouteResult::class, $this->router->match($request));

        return $handler->handle($request);
    }
}
