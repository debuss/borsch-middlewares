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
use Throwable;

/**
 * Class ErrorHandlerMiddleware
 *
 * Catch PHP errors and exceptions and display them on screen.
 * If a request attribute "env" with value "development" is found, then it will display details.
 *
 * It is a pretty simple middleware, feel free to replace it by your own in the pipeline; or extends it to add more
 * functionality (example: Monolog, Slack, ...).
 *
 * @package Borsch\Middleware
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $error) {
            /** @var ResponseFactoryInterface $response_factory */
            $response_factory = $request->getAttribute(ResponseFactoryInterface::class);
            $development = $request->getAttribute('env', 'production') == 'development';

            $response = $response_factory->createResponse(500);
            $response->getBody()->write(sprintf(
                '%s %s 500 Internal Server Error',
                $request->getMethod(),
                $request->getUri()->getPath()
            ));

            if ($development) {
                $response->getBody()->write(sprintf(
                    '<br><pre style="padding: 15px; border: 1px solid gray; background: whitesmoke;">(%s) %s [#%s]:<br>%s</pre>',
                    $error->getCode(),
                    $error->getFile(),
                    $error->getLine(),
                    $error->getTraceAsString()
                ));
            }

            return $response;
        }
    }
}
