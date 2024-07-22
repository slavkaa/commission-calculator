<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = new \App\AfterRefactoring\Bootstrap();
$bootstrap->init();

$commissionCalculatorFactory = $bootstrap->getCommissionCalculatorFactory();

try {
    $results = $commissionCalculatorFactory
        ->getCommissionCalculatorService()
        ->calculate($argv[1]);
    foreach ($results as $result) {
        echo $result->commission . PHP_EOL;
    }
} catch (Throwable $e) {
    $commissionCalculatorFactory
        ->getLogger()
        ->error($e->getMessage());
}