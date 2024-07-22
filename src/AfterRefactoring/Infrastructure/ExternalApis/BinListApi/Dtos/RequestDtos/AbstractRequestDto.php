<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\RequestDtos;

abstract class AbstractRequestDto
{
    protected string $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = $_ENV['BIN_LIST_API_BASE_URL'];
    }
    
    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    abstract public function getUrl(): string;
    
    abstract public function getMethod(): string;
}