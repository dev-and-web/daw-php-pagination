<?php

namespace Tests\DawPhpPagination;

use PHPUnit\Framework\TestCase;
use DawPhpPagination\Pagination;

class PaginationTest extends TestCase
{
    /**
     * Est appellée après chaque testMethod() de cette classe et de classes enfants
     * (si on met un tearDown() dans une classe enfant, c'est celle de la classe enfant qui sera appelé avant)
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $_GET = [];
    }
    
    public function testPagination()
    {
        $_GET['page'] = 3;

        $pagination = new Pagination();

        $pagination->paginate(42);

        $this->assertEquals(10, $pagination->getLimit());
        $this->assertEquals(20, $pagination->getOffset());
        $this->assertEquals(42, $pagination->getCount());
        $this->assertEquals(10, $pagination->getCountOnCurrentPage());
        $this->assertEquals(21, $pagination->getFrom());
        $this->assertEquals(30, $pagination->getTo());
        $this->assertEquals(3, $pagination->getCurrentPage());
        $this->assertEquals(5, $pagination->getNbPages());
        $this->assertEquals(10, $pagination->getPerPage());
        $this->assertTrue(is_string($pagination->render()));
        $this->assertTrue(is_string($pagination->perPage()));
    }

    public function testNotHasMorePage()
    {
        // Test is True :

        $_GET['page'] = 3;

        $pagination = new Pagination();

        $pagination->paginate(42);

        $this->assertTrue($pagination->hasMorePages());

        // Test is false :

        $_GET['page'] = 3;

        $pagination = new Pagination();

        $pagination->paginate(28);

        $this->assertFalse($pagination->hasMorePages());
    }

    public function testIsFirstPage()
    {
        // Test is True :

        $_GET['page'] = 1;

        $pagination = new Pagination();

        $pagination->paginate(28);

        $this->assertTrue($pagination->isFirstPage());

        // Test is False :

        $_GET['page'] = 2;

        $pagination = new Pagination();

        $pagination->paginate(28);

        $this->assertFalse($pagination->isFirstPage());
    }

    public function testIsLastPage()
    {
        // Test is True :

        $_GET['page'] = 3;

        $pagination = new Pagination();

        $pagination->paginate(28);

        $this->assertTrue($pagination->isLastPage());

        // Test is False :

        $_GET['page'] = 2;

        $pagination = new Pagination();

        $pagination->paginate(28);

        $this->assertFalse($pagination->isLastPage());
    }
}
