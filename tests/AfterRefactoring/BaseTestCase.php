<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected BootstrapForTests $bootstrap;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->bootstrap = new BootstrapForTests();
        $this->bootstrap->init();
    }
}