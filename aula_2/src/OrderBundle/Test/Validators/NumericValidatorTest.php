<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    #[DataProvider('valueProvider')]
    public function testIsValid($value, $expectedResult)
    {
        $numericValidator = new NumericValidator($value);

        $isValid = $numericValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsNumber' => ['value' => 20, 'expectedResult' => true],
            'shouldBeValidWhenValueIsNumbericString' => ['value' => '20', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsNotNumeric' => ['value' => 'abc', 'expectedResult' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
        ];
    }
}
