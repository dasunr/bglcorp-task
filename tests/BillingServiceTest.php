<?php

declare(strict_types=1);

use Carbon\Carbon;
use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase;
use BillingService\Classes\Product;
use BillingService\Classes\BillingService;
use BillingService\Enums\SubscriptionType;

class BillingServiceTest extends TestCase
{
    private BillingService $billingService;

    protected function setUp(): void
    {
        $this->billingService = new BillingService();
    }

    public function testMonthlySubscriptionFlatRate()
    {
        $faker = Faker::create();
        $product = new Product(
            SubscriptionType::MONTHLY,
            100,
            $faker->dateTimeBetween('-3 months', '-2 months')->format('Y-m-d'),
            Carbon::now()->subMonths(2)->toDateString(),
            Carbon::now()->subMonth()->toDateString()
        );
        $bill = $this->billingService->generateBill($product);
        $this->assertEquals(100, $bill->getAmount());
    }

    public function testAnnualSubscriptionFlatRate()
    {
        $faker = Faker::create();
        $product = new Product(
            SubscriptionType::ANNUAL,
            100,
            $faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d'),
            Carbon::now()->subYear()->toDateString(),
            Carbon::now()->subMonths(11)->toDateString()
        );
        $bill = $this->billingService->generateBill($product);
        $this->assertEquals(1200, $bill->getAmount());
    }

    public function testMonthlySubscriptionVariableRate()
    {
        $faker = Faker::create();
        $product = new Product(
            SubscriptionType::MONTHLY,
            5.0,
            $faker->dateTimeBetween('-3 months', '-2 months')->format('Y-m-d'),
            Carbon::now()->subMonths(2)->toDateString(),
            Carbon::now()->subMonth()->toDateString(),
            10,
        );
        $bill = $this->billingService->generateBill($product);
        $this->assertEquals(50, $bill->getAmount());
    }

    public function testAnnualSubscriptionVariableRate()
    {
        $faker = Faker::create();
        $product = new Product(
            SubscriptionType::ANNUAL,
            4.0,
            $faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d'),
            Carbon::now()->subYear()->toDateString(),
            Carbon::now()->subMonths(11)->toDateString(),
            200,
        );
        $bill = $this->billingService->generateBill($product);
        $this->assertEquals(800, $bill->getAmount());
    }

    // un-happy path
    public function testInvalidSubscriptionType()
    {
        $this->expectException(TypeError::class);
        $product = new Product(
            'invalid',
            100,
            '2023-01-01',
            '2023-01-01',
            '2023-02-01'
        );
        $this->billingService->generateBill($product);
    }

    public function testNegativeRate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Rate must be a positive value');
        $product = new Product(
            SubscriptionType::MONTHLY,
            -100,
            '2023-01-01',
            '2023-01-01',
            '2023-02-01'
        );
        $this->billingService->generateBill($product);
    }

    public function testFutureStartDate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Start date must be in the future');
        $product = new Product(SubscriptionType::MONTHLY, 100, '2025-01-01', '2023-01-01', '2023-02-01');
        $this->billingService->generateBill($product);
    }

    public function testBillingPeriodEndDateBeforeStartDate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Billing period dates are invalid');
        $product = new Product(
            SubscriptionType::MONTHLY,
            100,
            '2023-01-01',
            '2023-02-01',
            '2023-01-01'
        );
        $this->billingService->generateBill($product);
    }

    public function testZeroRate()
    {
        $this->expectException(InvalidArgumentException::class);
        $product = new Product(
            SubscriptionType::MONTHLY,
            0,
            '2023-01-01',
            '2023-01-01',
            '2023-02-01'
        );
        $this->billingService->generateBill($product);
    }
}
