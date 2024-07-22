<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\RequestDtos;

abstract class AbstractRequestDto
{
    protected string $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = $_ENV['EXCHANGE_RATE_API_BASE_URL'];
    }
    
    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    abstract public function getUrl(): string;
    
    abstract public function getMethod(): string;
    
    public function getApiKey(): string
    {
        return $_ENV['EXCHANGE_RATE_API_KEY'];
    }
}