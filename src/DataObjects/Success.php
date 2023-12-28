<?php

namespace Yormy\Apiresponse\DataObjects;

class Success
{
    const SUCCESS = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => '',
        'doc_url' => '',
    ];

    const SUCCESS_DELETED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.deleted.success',
        'doc_url' => '',
    ];

    const SUCCESS_CREATED = [
        'httpCode' => 201,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.created.success',
        'doc_url' => '',
    ];

    const SUCCESS_UPDATED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.updated.success',
        'doc_url' => '',
    ];

    const SUCCESS_STORED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'apiresponse::response.stored.success',
        'doc_url' => '',
    ];
}
