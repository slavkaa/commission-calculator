<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\RequestDtos;

class GetByIdRequestDto extends AbstractRequestDto
{
    // @phpstan-ignore-next-line
    public readonly array $params;
    
    public function __construct(
        public string $binId
    )
    {
        parent::__construct();
        
        $this->params = [
            'id' => $this->binId
        ];
    }
    
    public function getMethod(): string
    {
        return HttpMethodsEnum::GET;
    }
    
    public function getUrl(): string
    {
        return $this->getBaseUrl() . '/' . $this->binId;
    }
}