<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function jsonRequest($method, $uri, $data = [], $headers = [])
    {
        return $this->json($method, $uri, $data, $headers);
    }
}
