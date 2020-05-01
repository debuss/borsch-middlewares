<?php
/**
 * @author debuss-a
 */

namespace Borsch\Middleware;

use Laminas\Diactoros\Response;
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

    /** @var string */
    protected $environment;

    /**
     * ErrorHandlerMiddleware constructor.
     * @param string $environment
     */
    public function __construct(string $environment = 'production')
    {
        $this->environment = $environment;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $error) {
            $response = new Response('php://memory', 500);
            $response->getBody()->write(sprintf(
                '%s %s 500 Internal Server Error',
                $request->getMethod(),
                $request->getUri()->getPath()
            ));

            if ($this->environment == 'development') {
                $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
                $response->getBody()->write(sprintf(
                    '<br><pre style="padding: 15px; border: 1px solid gray; background: whitesmoke;">(%s) %s [#%s]:<br>> %s<br><h4>Trace</h4>%s</pre>',
                    $error->getCode(),
                    $error->getFile(),
                    $error->getLine(),
                    $error->getMessage(),
                    $error->getTraceAsString()
                ));
            }

            return $response;
        }
    }
}
