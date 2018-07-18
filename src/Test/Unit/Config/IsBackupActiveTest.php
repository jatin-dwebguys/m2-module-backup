<?php

namespace Itonomy\Backup\Test\Unit\Config\CheckoutConfiguration;

use Itonomy\Backup\Test\TestCase;

class IsBackupActiveTest extends TestCase
{
    public $instanceClass = IsPakjegemakActive::class;

    public function getValueProvider()
    {
        return [
            'is active' => [true],
            'is not active' => [true],
        ];
    }

    /**
     * @dataProvider getValueProvider
     *
     * @param $expected
     */
    public function testGetValue($expected)
    {
        $shippingOptions = $this->getFakeMock(ShippingOptions::class)->getMock();

        $expects = $shippingOptions->expects($this->once());
        $expects->method('isPakjegemakActive');
        $expects->willReturn($expected);

        /** @var IsPakjegemakActive $instance */
        $instance = $this->getInstance([
            'shippingOptions' => $shippingOptions,
        ]);

        $this->assertEquals($expected, $instance->getValue());
    }
}