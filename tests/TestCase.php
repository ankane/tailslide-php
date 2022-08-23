<?php

namespace Tests;

require(__DIR__ . "/Database.php");

use PHPUnit\Framework\TestCase as BaseTestCase;
use User;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        User::truncate();
    }
}
