<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\Cache;

interface StaticCacheInterface
{
    public function isHas(string $key): bool;
    
    public function get(string $key): mixed;
}