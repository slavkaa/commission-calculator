<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;

interface CommissionCalculatorServiceInterface
{
    /**
     * @return array<TransactionDto>
     */
    public function calculate(string $dataFilePath): array;
}