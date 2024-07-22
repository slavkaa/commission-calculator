<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\Builders;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;

interface MoneyDtoBuilderInterface
{
    public function build(float $amount, string $currency, string $bin): TransactionDto;
}