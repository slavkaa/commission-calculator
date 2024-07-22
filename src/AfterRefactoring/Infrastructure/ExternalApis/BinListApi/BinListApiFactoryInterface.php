<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi;

use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\RequestDtos\GetByIdRequestDto;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

interface BinListApiFactoryInterface
{
    public function getClient(): Client;
    
    public function getBinByIdRequest(string $id): GetByIdRequestDto;
    
    public function buildBinDto(string $alpha2countryCode): BinDto;
    
    public function getStaticCache(): StaticCache;
    
    public function getLogger(): LoggerInterface;
}