<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi;

use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\RequestDtos\GetByIdRequestDto;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class BinListApiFactory implements BinListApiFactoryInterface
{
    protected Level $logLevel;
    
    public function __construct()
    {
        $this->logLevel = Level::{$_ENV['BIN_LIST_API_LOG_LEVEL']};
    }
    
    public function getClient(): Client
    {
        return new Client();
    }
    
    public function getBinByIdRequest(string $id): GetByIdRequestDto
    {
        return new GetByIdRequestDto($id);
    }
    
    public function buildBinDto(string $alpha2countryCode): BinDto
    {
        return new BinDto($alpha2countryCode);
    }
    
    public function getStaticCache(): StaticCache
    {
        return new StaticCache(
            $this->getLogger()
        );
    }
    
    // @todo: depend on architecture and business logic Logger can be reused from CommissionCalculationFactory. 
    public function getLogger(): LoggerInterface
    {
        $log = new Logger('name');
        $log->pushHandler(
            new StreamHandler(
                __DIR__ . '/../../../../../var/logs/app.log',
                $this->logLevel
            )
        );
        
        return $log;
    }
}