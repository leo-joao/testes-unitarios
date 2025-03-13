<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    public function testShouldNotPayWhenAuthenticationFail()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                null
            ]
        ];
        $httpClient
            ->expects($this->once())
            ->method('send')
            ->willReturnMap($map);

        $name = 'Vinicius Oliveira';
        $creditCardNumber = 5555444488882222;
        $validity = new \DateTime('now');
        $value = 100;
        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function testShouldNotPayWhenFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $token = 'meu-token';
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                $token
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'token' => $token
                ],
                ['paid' => false]
            ]
        ];
        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->willReturnMap($map);

        $logger
            ->expects($this->once())
            ->method('log')
            ->with('Payment failed');

        $name = 'Vinicius Oliveira';
        $creditCardNumber = 5555444488882222;
        $value = 100;
        $validity = new \DateTime('now');
        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function testShouldSuccessfullyPayWhenGatewayReturnOk()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $name = 'Vinicius Oliveira';
        $creditCardNumber = 9999999999999999;
        $validity = new \DateTime('now');
        $value = 100;
        $token = 'meu-token';
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                'meu-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' => $creditCardNumber,
                    'validity' => $validity,
                    'value' => $value,
                    'token' => $token
                ],
                ['paid' => true]
            ]
        ];
        $httpClient
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturnMap($map);

        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(true, $paid);
    }
}
