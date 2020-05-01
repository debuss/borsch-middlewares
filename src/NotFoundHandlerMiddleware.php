<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class NotFoundHandler
 *
 * Generates a 404 Not Found response.
 *
 * @package Borsch\Middleware
 */
class NotFoundHandlerMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus(404, 'Not Found');

        $response->getBody()->write(sprintf(
            '%s %s 404 Not Found',
            $request->getMethod(),
            $request->getUri()->getPath()
        ));

        return $response;
    }
}