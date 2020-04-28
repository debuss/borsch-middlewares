<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ResponseTime
 *
 * Add a X-Response-Time in the response headers.
 *
 * @package Borsch\Middleware
 */
class ResponseTimeMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $start = $request->getServerParams()['REQUEST_TIME_FLOAT'] ?? microtime(true);
        $response = $handler->handle($request);

        return $response->withHeader(
            'X-Response-Time',
            sprintf(
                '%2.3fms',
                (microtime(true) - $start) * 1000
            )
        );
    }
}
