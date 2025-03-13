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

    public function testShouldSaveWhenGatewayReturnOkWithRetries()
    {
        $gateway = $this->createMock(Gateway::class);

        $paymentTrannsactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $paymentService = new PaymentService($gateway, $paymentTrannsactionRepository);

        $gateway->expects($this->atLeast(3))
            ->method('pay')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                true
            );

        $paymentTrannsactionRepository
            ->expects($this->once())
            ->method('save');

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);
        $paymentService->pay($customer, $item, $creditCard);
    }

    public function testShouldThrowExceptionWhenGatewayFails()
    {
        $gateway = $this->createMock(Gateway::class);

        $paymentTrannsactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $paymentService = new PaymentService($gateway, $paymentTrannsactionRepository);

        $gateway->expects($this->atLeast(3))
            ->method('pay')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                false
            );

        $paymentTrannsactionRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);
        $paymentService->pay($customer, $item, $creditCard);
    }
}
