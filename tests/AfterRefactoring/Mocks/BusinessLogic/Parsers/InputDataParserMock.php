<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring\Mocks\BusinessLogic\Parsers;

use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParser;

class InputDataParserMock extends InputDataParser
{
    protected array $data;
    
    public function setData(array $data): void
    {
        $this->data = $data;
    }
    
    /**
     * @return array<string>
     */
    protected function readFile(string $filename): array
    {
        return $this->data;
    }
}