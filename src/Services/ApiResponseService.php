<?php

declare(strict_types=1);

namespace Yormy\Apiresponse\Services;

use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Yormy\Apiresponse\DataObjects\Success;
use Yormy\Apiresponse\Exceptions\InvalidResponseConfigException;

class ApiResponseService
{
    private mixed $data = null;

    private string $idPrefix = 'main-';

    private ?int $httpCode = null;

    private ?string $redirectToUrl = null;

    private ?string $message = null;

    private ?string $messageKey = null;

    private ?string $redirectedFromUrl = null;

    private array $responseObject;

    private array $parameters = [];

    private bool $withoutMessage;

    private bool $asAbort = false;

    public function __construct()
    {
        $this->withoutMessage = false;
    }

    public function withData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withRedirect(string $redirectToUrl, bool $withSourceRedirect = true): self
    {
        $this->redirectToUrl = $redirectToUrl;

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = url();
        if ($withSourceRedirect) {
            $this->redirectedFromUrl = $urlGenerator->current();
        }

        return $this;
    }

    public function withRedirectRoute(string $redirectToRoute, bool $withSourceRedirect = true): self
    {
        return $this->withRedirect(route($redirectToRoute), $withSourceRedirect);
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

    public function withParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function withIdPrefix(string $idPrefix): self
    {
        $this->idPrefix = $idPrefix.'-';

        return $this;
    }

    public function errorResponse(array $responseObject): JsonResponse
    {
        //        $this->responseObject = $responseObject;
        //        return $this->returnWithStatus('error');

        $this->validateResponseObject($responseObject);

        $this->responseObject = $responseObject;

        $return = $this->returnWithStatus('error');

        if ($this->asAbort) {
            abort($return);
        }

        return $return;
    }

    public function abort(): self
    {
        $this->asAbort = true;

        return $this;
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

    private function validateResponseObject(array $responseObject): void
    {
        $allowedKeys = [
            'httpCode',
            'type',
            'code',
            'messageKey',
            'doc_url',
        ];

        foreach (array_keys($responseObject) as $key) {
            if (! in_array($key, $allowedKeys)) {
                throw new InvalidResponseConfigException("{$key} is not a valid key for the response object");
            }
        }
    }

    private function returnWithStatus(string $status): JsonResponse
    {
        //        $data = $this->buildStructure();
        //        $data['status'] = $status;
        //
        //        $returnHttpCode = (int)$this->getValue($this->responseObject, 'httpCode', (string)$this->httpCode);
        //        return response()->json($data, $returnHttpCode);

        $data = $this->buildStructure();
        $data['status'] = $status;

        $returnHttpCode = (int) $this->getValue($this->responseObject, 'httpCode', (string) $this->httpCode); // @phpstan-ignore-line

        return response()->json($data, $returnHttpCode); // @phpstan-ignore-line
    }

    //    private function buildStructure(): array
    //    {
    //        $responseObject = $this->responseObject;
    //
    //        $message = $this->determineMessage();
    //
    //        $data = $this->data;
    //        if ($data === null) {
    //            $data = new stdClass();
    //        }
    //        $response = [
    //            'type' => $this->getValue($responseObject, 'type'),
    //            'code' => $this->getValue($responseObject, 'code'),
    //            'message' => $message,
    //            'data' => $data,
    //        ];
    //
    //        $docUrl = $this->getValue($responseObject, 'doc_url');
    //        $response = $this->buildResponseValue('doc_url', $docUrl, $response);
    //
    //        $redirectTo = $this->getValue($responseObject, 'redirect_to', $this->redirectToUrl);
    //        $response = $this->buildResponseValue('redirect_to', $redirectTo, $response);
    //
    //        $response = $this->buildResponseValue('redirect_from', $this->redirectedFromUrl, $response);
    //
    //        return $response;
    //    }

    private function buildStructure(): array
    {
        $this->validateResponseObject($this->responseObject);

        $responseObject = $this->responseObject;

        $message = $this->determineMessage();

        $data = $this->data;

        if ($data === null) {
            $data = [];
            $data = new stdClass();
        }

        $response = [
            'id' => $this->idPrefix.Str::ulid(),
            'type' => $this->getValue($responseObject, 'type'),
            'code' => $this->getValue($responseObject, 'code'),
            'message' => $message,
            'data' => $data,
        ];

        if (! is_array($data)) {
            $decoded = json_encode($data);
            if ($decoded) {
                $data = json_decode($decoded, true); // flatten laravel-data
            } else {
                $data = [];
            }
        }

        $response['data'] = $data;
        if (is_array($data) && array_key_exists('meta', $data)) {
            $response['meta'] = $data['meta'];
        }
        if (is_array($data) && array_key_exists('data', $data)) {
            //   $response['data'] = $data['data'];
        }
        if (is_array($data) && array_key_exists('links', $data)) {
            $response['links'] = $data['links'];
        }

        $response['date'] = Carbon::now()->format('Y-m-d H:m:s');

        $docUrl = $this->getValue($responseObject, 'doc_url');
        $response = $this->buildResponseValue('doc_url', $docUrl, $response);

        $redirectTo = $this->getValue($responseObject, 'redirect_to', $this->redirectToUrl);
        $response = $this->buildResponseValue('redirect_to', $redirectTo, $response);

        return $this->buildResponseValue('redirect_from', $this->redirectedFromUrl, $response);
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

        return (string) $message; //@phpstan-ignore-line
    }

    private function buildResponseValue(string $key, mixed $value, array $response): array
    {
        if ($value) {
            $response[$key] = $value;
        }

        return $response;
    }

    private function getValue(array $responseObject, string $key, ?string $override = null): mixed
    {
        if ($override) {
            return $override;
        }

        if (! array_key_exists($key, $responseObject)) {
            if (! array_key_exists($key, $responseObject)) {
                return '';
            }

            return $responseObject[$key];
        }

        return $responseObject[$key];
    }
}
