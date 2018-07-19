<?php

namespace Itonomy\Backup\Test\Unit\Console\Command;

use Itonomy\Backup\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Itonomy\Backup\Console\Command\BackupCleanupCommand;

class BackupCleanupCommandTest extends TestCase
{
    public function setUp()
    {
        $this->command = $this->getFakeMock(BackupCleanupCommand::class)->disableOriginalConstructor()->getMock();
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertContains('List of active modules', $commandTester->getDisplay());
    }
}