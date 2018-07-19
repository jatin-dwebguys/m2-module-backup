<?php

namespace Itonomy\Backup\Test\Unit\Console\Command;

use Itonomy\Backup\Console\Command\BackupCleanupCommand;
use Symfony\Component\Console\Tester\CommandTester;

class BackupCompressCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CheckActiveModulesCommand
     */
    private $command;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $moduleList;

    public function setUp()
    {
        $this->command = new BackupCleanupCommand();
    }

    public function testExecute()
    {
        $this->moduleList->expects($this->once())->method('getNames')->willReturn([]);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertContains('List of active modules', $commandTester->getDisplay());
    }
}