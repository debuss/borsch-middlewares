<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
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
                return new RedirectResponse((string)$uri, 301);
            }

            $request = $request->withUri($uri);
        }

        return $handler->handle($request);
    }
}
