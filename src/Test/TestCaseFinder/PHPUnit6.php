<?php

namespace Itonomy\Backup\Test;

class TestCaseFinder extends \PHPUnit\Framework\TestCase
{
    public function getMock($className)
    {
        return $this->createMock($className);
    }
}