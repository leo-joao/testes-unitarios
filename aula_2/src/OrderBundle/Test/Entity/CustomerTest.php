<?php

namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    #[DataProvider('customerAllowedDataProvider')]

    public function testIsAllowedToOrder($isActive, $isBlocked, $expectedAllowed)
    {
        $customer = new Customer(
            $isActive,
            $isBlocked,
            'Léo João',
            '+5547988887777'
        );

        $isAllowed = $customer->isAllowedToOrder();

        $this->assertEquals($expectedAllowed, $isAllowed);
    }

    public static function customerAllowedDataProvider()
    {
        return [
            'shouldBeAllowedWhenIsActiveAndNotBlocked' => [
                'isActive' => true,
                'isBlocked' => false,
                'expectedAllowed' => true,
            ],
            'shouldNotBeAllowedWhenIsActiveButIsBlocked' => [
                'isActive' => true,
                'isBlocked' => true,
                'expectedAllowed' => false,
            ],
            'shouldNotBeAllowedWhenIsNotActiveAndNotBlocked' => [
                'isActive' => false,
                'isBlocked' => false,
                'expectedAllowed' => false,
            ],
            'shouldNotBeAllowedWhenIsNotActiveAndIsBlocked' => [
                'isActive' => false,
                'isBlocked' => true,
                'expectedAllowed' => false,
            ]
        ];
    }
}
