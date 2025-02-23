<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    #[DataProvider('badWordsDataProvider')]
    public function testHasBadWords($badWordsList, $text, $foundBadWords)
    {
        $badWordsRepository = $this->createMock(BadWordsRepository::class);

        $badWordsRepository->method('findAllAsArray')->willReturn($badWordsList);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        $hasBadWords = $badWordsValidator->hasBadWords($text);

        $this->assertEquals($foundBadWords, $hasBadWords);
    }

    public static function badWordsDataProvider()
    {
        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => ['bobo', 'burro', 'feio'],
                'text' => 'Seu restaurante é feio',
                'foundBadWords' => true,
            ],
            'shouldNotFindWhenHasNoBadWords' => [
                'badWordsList' => ['bobo', 'burro', 'feio'],
                'text' => 'Fazer o lanche sem cebola',
                'foundBadWords' => false,
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => ['bobo', 'burro', 'feio'],
                'text' => '',
                'foundBadWords' => false,
            ],
            'shouldNotFindWhenWordListIsEmpty' => [
                'badWordsList' => [],
                'text' => '',
                'foundBadWords' => false,
            ]
        ];
    }
}
