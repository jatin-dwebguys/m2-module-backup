<?php

namespace Itonomy\Backup\Test\Unit;

use Itonomy\Backup\Test\TestCase;
use Itonomy\Backup\Exception;

class ExceptionTest extends TestCase
{
    public function testShowsTheMessage()
    {
        $exception = new \Itonomy\Backup\Exception('This is my test message');
        $this->assertEquals('This is my test message', $exception->getMessage());
    }
    public function testAcceptsAnPhrase()
    {
        $exception = new \Itonomy\Backup\Exception(__('This is my test message'));
        $this->assertEquals('This is my test message', $exception->getMessage());
    }
    public function testAddTheCodeIfSupplied()
    {
        $exception = new \Itonomy\Backup\Exception('This is my test message', -999);
        $this->assertEquals('[-999] This is my test message', $exception->getMessage());
    }
}