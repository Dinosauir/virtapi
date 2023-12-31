<?php

namespace Tests;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected Generator $faker;

    use CreatesApplication, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->withoutExceptionHandling();
        Artisan::call('migrate:refresh');
    }

    public function __get($key)
    {
        if ($key === 'faker') {
            return $this->faker;
        }

        throw new Exception('Unknown Key Requested');
    }

}
