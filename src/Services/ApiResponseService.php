<?php

namespace Yormy\Apiresponse\Services;

use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Yormy\Apiresponse\DataObjects\Success;

class ApiResponseService
{
    private $data;

    private ?int $httpCode = null;

    private ?string $redirectToUrl = null;

    private ?string $message = null;

    private ?string $messageKey = null;

    private ?string $redirectedFromUrl = null;

    private array $responseObject;

    private array $parameters = [];

    private bool $withoutMessage;

    public function __construct()
    {
        $this->withoutMessage = false;
    }

    public function withData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withRedirect(string $redirectToUrl): self
    {
        $this->redirectToUrl = $redirectToUrl;

        $this->redirectedFromUrl = url()->current();

        return $this;
    }

    public function withRedirectRoute(string $redirectToRoute): self
    {
        return $this->withRedirect(route($redirectToRoute));
    }

    public function withoutMessage(): self
    {
        $this->withoutMessage = true;

        return $this;
    }

    public function withMessageKey(string $messageKey): self
    {
        $this->messageKey = $messageKey;

        return $this;
    }

    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function withParameters($parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function errorResponse(array $responseObject): JsonResponse
    {
        $this->responseObject = $responseObject;

        return $this->returnWithStatus('error');
    }

    private function returnWithStatus(string $status): JsonResponse
    {
        $data = $this->buildStructure();
        $data['status'] = $status;

        $returnHttpCode = (int) $this->getValue($this->responseObject, 'httpCode', (string) $this->httpCode);

        return response()->json($data, $returnHttpCode);
    }

    private function buildStructure(): array
    {
        $responseObject = $this->responseObject;

        $message = $this->determineMessage();

        $data = $this->data;
        if ($data === null) {
            $data = new stdClass();
        }
        $response = [
            'type' => $this->getValue($responseObject, 'type'),
            'code' => $this->getValue($responseObject, 'code'),
            'message' => $message,
            'data' => $data,
        ];

        $docUrl = $this->getValue($responseObject, 'doc_url');
        $response = $this->buildResponseValue('doc_url', $docUrl, $response);

        $redirectTo = $this->getValue($responseObject, 'redirect_to', $this->redirectToUrl);
        $response = $this->buildResponseValue('redirect_to', $redirectTo, $response);

        $response = $this->buildResponseValue('redirect_from', $this->redirectedFromUrl, $response);

        return $response;
    }

    private function determineMessage(): string
    {
        if ($this->withoutMessage) {
            return '';
        }

        $message = $this->message;

        if (! $message &&
            array_key_exists('messageKey', $this->responseObject)
        ) {
            $message = '';
            if ($this->responseObject['messageKey']) {
                $message = __($this->responseObject['messageKey'], $this->parameters);
            }
        }

        if ($this->messageKey) {
            $message = __($this->messageKey, $this->parameters);
        }

        return (string) $message;
    }

    private function buildResponseValue($key, $value, $response)
    {
        if ($value) {
            $response[$key] = $value;
        }

        return $response;
    }

    private function getValue(array $responseObject, string $key, string $override = null): string
    {
        if ($override) {
            return $override;
        }

        if (! array_key_exists($key, $responseObject)) {
            return '';
        }

        return $responseObject[$key];
    }

    public function successResponse(): JsonResponse
    {
        $this->responseObject = Success::SUCCESS;

        return $this->returnWithStatus('success');
    }

    public function successResponseCreated(): JsonResponse
    {
        $this->responseObject = Success::SUCCESS_CREATED;

        return $this->returnWithStatus('success');
    }

    public function successResponseUpdated(): JsonResponse
    {
        $this->responseObject = Success::SUCCESS_UPDATED;

        return $this->returnWithStatus('success');
    }

    public function successResponseStored(): JsonResponse
    {
        $this->responseObject = Success::SUCCESS_STORED;

        return $this->returnWithStatus('success');
    }

    public function successResponseDeleted(): JsonResponse
    {
        $this->responseObject = Success::SUCCESS_DELETED;

        return $this->returnWithStatus('success');
    }
}
