<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    #[DataProvider('valueProvider')]
    public function testIsValid($value, $expectedResult)
    {
        $notEmptyValidator = new NotEmptyValidator($value);

        $isValid = $notEmptyValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsNotEmpty' => ['value' => 'abc', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
        ];
    }
}
