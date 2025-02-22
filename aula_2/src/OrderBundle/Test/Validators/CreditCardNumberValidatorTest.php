<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase
{
    #[DataProvider('valueProvider')]
    public function testIsValid($value, $expectedResult)
    {
        $creditCardValidator = new CreditCardNumberValidator($value);

        $isValid = $creditCardValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsCreditCard' => ['value' => 4928148506666302, 'expectedResult' => true],
            'shouldBeValidWhenValueIsCreditCardAsString' => ['value' => '4928148506666302', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsNotCreditCard' => ['value' => 123, 'expectedResult' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
        ];
    }
}
