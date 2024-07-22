<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\RequestDtos;

use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\RequestDtos\HttpMethodsEnum;

class GetLatestRatesRequestDto extends AbstractRequestDto
{
    public function getMethod(): string
    {
        return HttpMethodsEnum::GET;
    }
    
    public function getUrl(): string
    {
        return $this->getBaseUrl() . '/latest?access_key=' . $this->getApiKey();
    }
}