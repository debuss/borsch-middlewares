<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class TrailingSlashMiddleware
 *
 * If you want to redirect/rewrite all URLs that end in a / to the non-trailing / equivalent,
 * then you can add this middleware.
 *
 * @package Borsch\Middleware
 */
class TrailingSlashMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     * @link http://www.slimframework.com/docs/v4/cookbook/route-patterns.html
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path != '/' && substr($path, -1) == '/') {
            // Permanently redirect paths with a trailing slash to their non-trailing equivalent
            $uri = $uri->withPath(rtrim($path, '/'));

            if ($request->getMethod() == 'GET') {
                /** @var ResponseFactoryInterface $response_factory */
                $response_factory = $request->getAttribute(ResponseFactoryInterface::class);

                return $response_factory->createResponse(301)->withHeader('Location', (string)$uri);
            }

            $request = $request->withUri($uri);
        }

        return $handler->handle($request);
    }
}
