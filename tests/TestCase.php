<?php

namespace Yormy\Apiresponse\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Yormy\Apiresponse\ApiresponseServiceProvider;
use Yormy\AssertLaravel\Helpers\AssertJsonMacros;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TestConfig::setup();

        $this->withoutExceptionHandling();

        AssertJsonMacros::register();
    }

    /**
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            ApiresponseServiceProvider::class,
        ];
    }
}
