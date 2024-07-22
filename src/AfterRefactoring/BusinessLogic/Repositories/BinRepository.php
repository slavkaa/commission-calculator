<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\Repositories;

use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiClientInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;

readonly class BinRepository
{
    public function __construct(
        private BinListApiClientInterface $api
    )
    {}
    
    public function getBinById(string $id): BinDto
    {
        return $this->api->getBinById($id);
    }
}