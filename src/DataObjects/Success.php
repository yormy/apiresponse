<?php

declare(strict_types=1);

namespace Yormy\Apiresponse\DataObjects;

class Success
{
    public const SUCCESS = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => '',
        'doc_url' => '',
    ];

    public const SUCCESS_DELETED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.deleted.success',
        'doc_url' => '',
    ];

    public const SUCCESS_CREATED = [
        'httpCode' => 201,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.created.success',
        'doc_url' => '',
    ];

    public const SUCCESS_UPDATED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.updated.success',
        'doc_url' => '',
    ];

    public const SUCCESS_STORED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.stored.success',
        'doc_url' => '',
    ];
}
