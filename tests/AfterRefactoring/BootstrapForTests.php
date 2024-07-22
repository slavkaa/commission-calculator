<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring;

use App\AfterRefactoring\Bootstrap;

class BootstrapForTests extends Bootstrap
{
    protected function getListOfEnvFilesToLoad(): array
    {
        return ['.env.test'];
    }
}