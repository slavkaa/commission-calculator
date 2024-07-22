<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos;

class BinDto
{
    public function __construct(
        public string $countryAlpha2Code,
        // can be extended in future if we will need more data from BinList API.
    )
    {}
}