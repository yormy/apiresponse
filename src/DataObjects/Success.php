<?php

namespace Yormy\Apiresponse\DataObjects;

class Success
{
    const SUCCESS = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => "",
        'doc_url' => ""
    ];

    const SUCCESS_DELETED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'bedrock-core::general.deleted.success',
        'doc_url' => ''
    ];

    const SUCCESS_CREATED = [
        'httpCode' => 201,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'bedrock-core::general.created.success',
        'doc_url' => ''
    ];

    const SUCCESS_UPDATED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'bedrock-core::general.updated.success',
        'doc_url' => ''
    ];

    const SUCCESS_STORED = [
        'httpCode' => 200,
        'type' => 'core',
        'code' => 'SUCCESS',
        'messageKey' => 'bedrock-core::general.stored.success',
        'doc_url' => ''
    ];
}
