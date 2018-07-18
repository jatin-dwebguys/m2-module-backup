<?php

namespace Itonomy\Backup\Test\Unit\Console\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Magento\CommandExample\Console\Command\CheckActiveModulesCommand;

class CheckActiveModulesCommandTest extends \PHPUnit_Framework_TestCase
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
        $this->moduleList = $this->getMockForAbstractClass('Magento\Framework\Module\ModuleListInterface');
        $this->command = new CheckActiveModulesCommand($this->moduleList);
    }

    public function testExecute()
    {
        $command = $this->getFakeMock(ShippingOptions::class)->getMock();


        $this->moduleList->expects($this->once())->method('getNames')->willReturn([]);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertContains('List of active modules', $commandTester->getDisplay());
    }
}