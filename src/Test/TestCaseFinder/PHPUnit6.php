<?php

namespace Itonomny\Backup\Test;

class TestCaseFinder extends \PHPUnit\Framework\TestCase
{
    public function getMock($className)
    {
        return $this->createMock($className);
    }
}