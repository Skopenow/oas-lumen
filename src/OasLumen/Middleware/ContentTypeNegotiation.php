<?php

namespace DanBallance\OasLumen\Middleware;

use Closure;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Exceptions\JsonErrorFactory;
use DanBallance\OasLumen\Http\Psr7Factory;
use DanBallance\OasLumen\Http\RequestHeadersFactory;

class ContentTypeNegotiation
{
    protected $spec;
    protected $jsonErrorFactory;
    protected $psr7Factory;
    protected $requestHeadersFactory;

    public function __construct(
        Specification $spec,
        JsonErrorFactory $factory,
        Psr7Factory $psr7Factory,
        RequestHeadersFactory $requestHeadersFactory
    ) {
        $this->spec = $spec;
        $this->jsonErrorFactory = $factory;
        $this->psr7Factory = $psr7Factory;
        $this->requestHeadersFactory = $requestHeadersFactory;
    }

    /**
     * @see https://tools.ietf.org/html/rfc7231#section-5.3.2
     * 
     * "A request without any Accept header field implies that the user agent
     * will accept any media type in response."
     * 
     * "If the header field is
     * present in a request and none of the available representations for
     * the response have a media type that is listed as acceptable, the
     * origin server can either honor the header field by sending a 406 (Not
     * Acceptable) response or disregard the header field by treating the
     * response as if it is not subject to content negotiation."
     */
    public function handle($request, Closure $next, $operationId)
    {
        $operation = $this->spec->getOperation($operationId);
        if ($operation) {
            $psr7Request = $this->psr7Factory->requestFromLumen($request);
            $requestHeaders = $this->requestHeadersFactory->make($psr7Request);
            $requestContentTypes = $operation->getRequestContentTypes();
            if (!$requestHeaders->supportedContentType($requestContentTypes)) {
                return $this->jsonErrorFactory->make(415);
            }
            $responseContentTypes = $operation->getResponseContentTypes();
            if (!$requestHeaders->acceptable($responseContentTypes)) {
                return $this->jsonErrorFactory->make(406);
            }
        }
        return $next($request);
    }
}
