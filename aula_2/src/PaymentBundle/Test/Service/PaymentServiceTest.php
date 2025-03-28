<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private $gateway;
    private $paymentTrannsactionRepository;
    private $paymentService;
    private $customer;
    private $item;
    private $creditCard;

    // this one runs once for the whole test case
    public static function setUpBeforeClass(): void
    {

    }

    // this one runs once for each test method
    public function setUp(): void
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->paymentTrannsactionRepository = $this->createMock(PaymentTransactionRepository::class);
        $this->paymentService = new PaymentService($this->gateway, $this->paymentTrannsactionRepository);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    public function testShouldSaveWhenGatewayReturnOkWithRetries()
    {
        $this->gateway->expects($this->atLeast(3))
            ->method('pay')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                true
            );

        $this->paymentTrannsactionRepository
            ->expects($this->once())
            ->method('save');

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    public function testShouldThrowExceptionWhenGatewayFails()
    {
        $this->gateway->expects($this->atLeast(3))
            ->method('pay')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                false
            );

        $this->paymentTrannsactionRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);
        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    protected function tearDown(): void
    {
        unset($this->gateway);
    }
}

