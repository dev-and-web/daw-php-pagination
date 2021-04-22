<?php

namespace Tests\DawPhPagination\Support\Request;

use PHPUnit\Framework\TestCase;
use DawPhpPagination\Support\Request\Request;

class RequestTest extends TestCase
{
    public function testRequestGet()
    {
        $_GET['p'] = 1;

        $request = new Request();

        $this->assertFalse($request->getGet()->has('pp'));

        $this->assertTrue($request->getGet()->has('p'));

        $this->assertEquals(1, $request->getGet()->get('p'));

        $_GET = [];
    }
}
