<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\Dtos;

readonly class TransactionDto
{
    public function __construct(
        public float  $amount,
        public string $currencyAlpha3Code,
        public string $bin,
        public string $countryAlpha2Code,
        public float $commission = 0
    )
    {}
}