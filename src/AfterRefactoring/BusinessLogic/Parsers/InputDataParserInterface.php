<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\Parsers;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;

interface InputDataParserInterface
{
    /**
     * @return array<TransactionDto>
     */
    public function parse(string $dataFilePath): array;
}