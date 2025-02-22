<?php

namespace OrderBundle\Validators\Test;

use DateTime;
use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase
{
    #[DataProvider('valueProvider')]
    public function testIsValid($value, $expectedResult)
    {
        $creditCardExpirationDate = new DateTime($value);
        $creditCardExpirationValidator = new CreditCardExpirationValidator($creditCardExpirationDate);

        $isValid = $creditCardExpirationValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider(): array
    {
        return [
            'shouldBeValidWhenDateIsNotExpired' => ['value' => '2035-01-01', 'expectedResult' => true],
            'shouldNotBeValidWhenDateIsExpired' => ['value' => '2015-01-01', 'expectedResult' => false],
        ];
    }
}
