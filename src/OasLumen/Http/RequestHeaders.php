<?php

namespace DanBallance\OasLumen\Http;

use Psr\Http\Message\ServerRequestInterface;

class RequestHeaders
{
    protected $headerFactory;
    protected $request;
    protected $headers = [];

    public function __construct(
        HeaderFactory $headerFactory,
        ServerRequestInterface $request
    ) {
        $this->headerFactory = $headerFactory;
        $this->request = $request;
    }

    public function get(
        string $origHeaderName
    ): ?HeaderInterface {
        $lowerHeaderName = strtolower($origHeaderName);
        if (!isset($this->headers[$lowerHeaderName])) {
            $headerLine = $this->request->getHeaderLine($lowerHeaderName);
            if (!$headerLine) {
                return null;
            }
            $this->headers[$lowerHeaderName] = $this->headerFactory->make(
                $origHeaderName,
                $headerLine
            );
        }
        return $this->headers[$lowerHeaderName];
    }

    public function supportedContentType(
        array $requestContentTypes
    ): bool {
        if (!$requestContentTypes) {
            return true;
        }
        $contentTypeHeader = $this->get('Content-Type');
        if (!$contentTypeHeader) {
            return true;
        }
        foreach ($contentTypeHeader->values() as $value) {
            if (in_array($value->value(), $requestContentTypes)) {
                return true;
            }
        }
        return false;
    }

    public function acceptable(
        array $responseContentTypes
    ): bool {
        $acceptHeader = $this->get('Accept');
        if (!$acceptHeader) {
            return true;
        }
        foreach ($acceptHeader->values() as $value) {
            foreach ($responseContentTypes as $responseContentType) {
                if ($this->mimeTypeMatches($value, $responseContentType)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function mostAcceptableMimeType(
        array $responseContentTypes
    ): string {
        $acceptHeader = $this->get('Accept');
        if (!$acceptHeader) {
            return null;
        }
        foreach ($acceptHeader->values() as $value) {
            foreach ($responseContentTypes as $responseContentType) {
                if ($this->mimeTypeMatches($value, $responseContentType)) {
                    return $responseContentType;
                }
            }
        }
        return null;
    }

    protected function mimeTypeMatches(
        HeaderValueAcceptInterface $headerValue,
        string $responseContentType
    ): bool {
        [$type, $subType] = explode('/', $responseContentType);
        if ($headerValue->type() != '*' && $headerValue->type() != $type) {
            return false;
        }
        if ($headerValue->subType() != '*' && $headerValue->subType() != $subType) {
            return false;
        }
        return true;
    }
}
