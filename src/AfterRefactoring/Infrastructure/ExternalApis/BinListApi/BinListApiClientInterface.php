<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi;

use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;

interface BinListApiClientInterface
{
    public function getBinById(string $id): BinDto;
}