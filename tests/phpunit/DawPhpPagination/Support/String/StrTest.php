<?php

namespace Tests\DawPhpValidator\Support\String;

use PHPUnit\Framework\TestCase;
use DawPhpPagination\Support\String\Str;

class StrTest extends TestCase
{
    public function testAndIfHasQueryParams()
    {
        $this->assertTrue(is_string(Str::andIfHasQueryParams(['page', 'pp'])));
    }

    public function testInputHiddenIfHasQueryParams()
    {
        $this->assertTrue(is_string(Str::inputHiddenIfHasQueryParams(['page', 'pp'])));
    }
}
