<?php

namespace Itonomy\Backup\Test\Unit\Console\Command;

use Itonomy\Backup\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Itonomy\Backup\Console\Command\BackupCompressCommand;

class BackupCompressCommandTest extends TestCase
{
    public function setUp()
    {
        $this->command = $this->getFakeMock(BackupCompressCommand::class)->disableOriginalConstructor()->getMock();
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertNull($commandTester->getStatusCode());
    }
}