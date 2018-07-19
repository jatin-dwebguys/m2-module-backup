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
        $this->moduleList->expects($this->once())->method('getNames')->willReturn([]);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertContains('List of active modules', $commandTester->getStatusCode());
    }
}