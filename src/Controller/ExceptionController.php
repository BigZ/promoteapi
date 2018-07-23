<?php

namespace App\Controller;

use FOS\RestBundle\Util\ExceptionValueMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Custom ExceptionController that renders to json
 */
class ExceptionController
{
    /**
     * @var ExceptionValueMap
     */
    private $exceptionCodes;

    public function __construct(ExceptionValueMap $exceptionCodes) {
        $this->exceptionCodes = $exceptionCodes;
    }

    /**
     * Converts an Exception to a Response.
     *
     * @param Request                   $request
     * @param \Exception|\Throwable     $exception
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function showAction(Request $request, \Exception $exception)
    {
        $code = $this->getStatusCode($exception);
        return new Response(
            json_encode(
                ['error' => $exception->getMessage()],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ),
            $code,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * Determines the status code to use for the response.
     *
     * @param \Exception $exception
     *
     * @return int
     */
    protected function getStatusCode(\Exception $exception)
    {
        // If matched
        if ($statusCode = $this->exceptionCodes->resolveException($exception)) {
            return $statusCode;
        }

        // Otherwise, default
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
