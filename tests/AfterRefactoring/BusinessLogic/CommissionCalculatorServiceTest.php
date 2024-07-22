<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring\BusinessLogic;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\AfterRefactoring\BaseTestCase;
use Tests\AfterRefactoring\Mocks\CommissionCalculatorFactoryMock;

class CommissionCalculatorServiceTest extends BaseTestCase
{
    protected CommissionCalculatorFactoryMock $factory;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__. '/../../../', ['.env.test']);
        $dotenv->load();
        
        $this->factory = new CommissionCalculatorFactoryMock();
    }
    
    #[DataProvider('dataProvider')]
    public function testCalculate(array $input, array $asserts)
    {
        $this->factory->setParserResponse($input['parserOutput']);
        $this->factory->setBinListApiMockResponses($input['binListApiOutput']);
        $this->factory->setExchangeRatesApiMockResponses($input['exchangeRatesApiOutput']);
        
        $service = $this->factory->createCommissionCalculatorService();
        
        $result = $service->calculate('dummy/path/to.file');
        
        $this->assertIsArray($result);
        $this->assertCount($asserts['counter'], $result);
        
        if( $asserts['counter'] > 0 ) {
            $this->assertEquals($asserts['commission'], $result[0]->commission);
        }
    }
    
    public static function dataProvider()
    {
        yield 'Case 1. EUR. EU.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"45717360","amount":"234.00","currency":"EUR"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(
                        200, 
                        [], 
                        '{"country":{"alpha2":"DK"}}'
                    )
                ]),
                // No rates will be requested to calculate EUR commission
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(200, [], '{"base":"EUR", "rates":{"USD":1.2}}')
                ]), 
            ],
            'asserts' => [
                'counter' => 1,
                'commission' => 2.34,
            ]
        ];
        yield 'Case 2. EUR. non EU.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"45717360","amount":"468.00","currency":"EUR"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(
                        200, 
                        [], 
                        '{"country":{"alpha2":"UA"}}'
                    )
                ]),
                // No rates will be requested to calculate EUR commission
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(200, [], '{"base":"EUR", "rates":{"USD":1.2}}')
                ]), 
            ],
            'asserts' => [
                'counter' => 1, 
                'commission' => 9.36, // Non EU coefficient twice bigger that EU
            ]
        ];
        
        yield 'Case 3. USD. EU.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"516793","amount":"50.00","currency":"USD"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(
                        200,
                        [],
                        '{"country":{"alpha2":"LT"}}'
                    )
                ]),
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(200, [], '{"base":"EUR", "rates":{"USD":1.2}}')
                ]),
            ],
            'asserts' => [
                'counter' => 1,
                'commission' => 0.42,
            ]
        ];
        
        yield 'Case 4. USD. Non EU.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"516793","amount":"50.00","currency":"USD"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(
                        200,
                        [],
                        '{"country":{"alpha2":"UA"}}'
                    )
                ]),
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(200, [], '{"base":"EUR", "rates":{"USD":1.2}}')
                ]),
            ],
            'asserts' => [
                'counter' => 1,
                'commission' => 0.84,
            ]
        ];
        
        yield 'Case 5. 500 Error as Rates API response.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"516793","amount":"50.00","currency":"USD"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(
                        200,
                        [],
                        '{"country":{"alpha2":"UA"}}'
                    )
                ]),
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(500, [], '')
                ]),
            ],
            'asserts' => [
                'counter' => 0,
                'commission' => 0,
            ]
        ];
        
        yield 'Case 6. 500 Error as BIN API response.' => [
            'input' => [
                'parserOutput' => [
                    '{"bin":"516793","amount":"50.00","currency":"USD"}'
                ],
                'binListApiOutput' => new MockHandler([
                    new Response(500, [], '')
                ]),
                'exchangeRatesApiOutput' => new MockHandler([
                    new Response(200, [], '{"base":"EUR", "rates":{"USD":1.2}}')
                ]),
            ],
            'asserts' => [
                'counter' => 0,
                'commission' => 0,
            ]
        ];
    }
}