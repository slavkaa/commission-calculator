<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\CommissionCalculator;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;

interface CommissionCalculatorInterface
{
    public function calculate(TransactionDto $dto): float;
}