<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\SePayService;
use App\Services\SettingService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SePayPaymentTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);

        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create([
            'name' => 'Nguyễn Văn Học Viên',
            'email' => 'student.sepay@colearn.test',
            'balance' => 0.00,
        ]);
        $this->user->assignRole('student');

        // Set test SePay bank config
        $settingService = app(SettingService::class);
        $settingService->set('sepay', 'bank_id', 'MBBank');
        $settingService->set('sepay', 'account_no', '0123456789');
        $settingService->set('sepay', 'account_name', 'COLEARN ACADEMY');
        $settingService->set('sepay', 'api_key', 'test_sepay_api_secret_key_123');
    }

    public function test_sepay_service_generates_official_sepay_qr_url(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-TOPUP-20260819-ABCDE',
            'order_type' => 'topup',
            'user_id' => $this->user->id,
            'subtotal' => 200000,
            'discount_amount' => 0,
            'total_amount' => 200000,
            'status' => 'pending',
            'payment_method' => 'sepay',
        ]);

        $sePayService = app(SePayService::class);
        $qrData = $sePayService->generateSePayQrData($order);

        $this->assertEquals('SePay', $qrData['gateway']);
        $this->assertEquals('MBBank', $qrData['bank_id']);
        $this->assertEquals('0123456789', $qrData['account_no']);
        $this->assertEquals(200000, $qrData['amount']);
        $this->assertStringContainsString('qr.sepay.vn/img', $qrData['qr_url']);
        $this->assertStringContainsString('bank=MBBank', $qrData['qr_url']);
        $this->assertStringContainsString('acc=0123456789', $qrData['qr_url']);
        $this->assertStringContainsString('des=ORD-TOPUP-20260819-ABCDE', $qrData['qr_url']);
    }

    public function test_sepay_webhook_authenticates_api_key(): void
    {
        $payload = [
            'id' => 99999,
            'gateway' => 'MBBank',
            'transferType' => 'in',
            'transferAmount' => 200000,
            'content' => 'ORD-TOPUP-20260819-INVALID',
        ];

        // 1. Unauthorized request (wrong key)
        $response = $this->postJson(route('payment.sepay.webhook'), $payload, [
            'Authorization' => 'Apikey wrong_secret_key',
        ]);
        $response->assertStatus(401);

        // 2. Authorized request (correct key)
        $response = $this->postJson(route('payment.sepay.webhook'), $payload, [
            'Authorization' => 'Apikey test_sepay_api_secret_key_123',
        ]);
        // Returns 400 because order ORD-TOPUP-20260819-INVALID doesn't exist, but authentication passed (not 401)
        $this->assertNotEquals(401, $response->status());
    }

    public function test_sepay_webhook_fulfills_topup_order_and_credits_wallet(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-TOPUP-20260819-TOPUP1',
            'order_type' => 'topup',
            'user_id' => $this->user->id,
            'subtotal' => 500000,
            'discount_amount' => 0,
            'total_amount' => 500000,
            'status' => 'pending',
            'payment_method' => 'sepay',
        ]);

        $this->assertEquals(0, $this->user->fresh()->balance);

        $payload = [
            'id' => 12345678,
            'gateway' => 'MBBank',
            'transactionDate' => '2026-08-19 19:30:00',
            'accountNumber' => '0123456789',
            'transferType' => 'in',
            'transferAmount' => 500000,
            'content' => 'Chuyen tien hoc phi ORD-TOPUP-20260819-TOPUP1 tai CoLearn',
            'referenceCode' => 'MB12345678',
        ];

        $response = $this->postJson(route('payment.sepay.webhook'), $payload, [
            'Authorization' => 'Apikey test_sepay_api_secret_key_123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify order is paid
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->paid_at);

        // Verify user balance is credited
        $this->assertEquals(500000, $this->user->fresh()->balance);

        // Verify transaction record was created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'order_id' => $order->id,
            'type' => 'in',
            'action' => 'deposit_bank',
            'amount' => 500000,
        ]);
    }

    public function test_sepay_webhook_fulfills_course_purchase_order_and_enrolls(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $category = Category::firstOrCreate(
            ['slug' => 'test-sepay-cat'],
            ['name' => 'Test SePay Category', 'sort_order' => 1],
        );

        $course = Course::create([
            'teacher_id' => $teacher->id,
            'category_id' => $category->id,
            'title' => 'Khóa Học Laravel Nâng Cao SePay',
            'slug' => 'khoa-hoc-laravel-nang-cao-sepay',
            'price' => 1200000,
            'discount_price' => 990000,
            'level' => 'all',
            'status' => 'published',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20260819-COURSE1',
            'order_type' => 'course',
            'user_id' => $this->user->id,
            'subtotal' => 990000,
            'discount_amount' => 0,
            'total_amount' => 990000,
            'status' => 'pending',
            'payment_method' => 'sepay',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'course_id' => $course->id,
            'price' => 990000,
        ]);

        $payload = [
            'id' => 88888888,
            'gateway' => 'MBBank',
            'transferType' => 'in',
            'transferAmount' => 990000,
            'content' => 'Thanh toan don hang ORD-20260819-COURSE1',
        ];

        $response = $this->postJson(route('payment.sepay.webhook'), $payload, [
            'Authorization' => 'Apikey test_sepay_api_secret_key_123',
        ]);

        $response->assertStatus(200);

        // Verify order is paid
        $this->assertEquals('paid', $order->fresh()->status);

        // Verify user is enrolled
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->user->id,
            'course_id' => $course->id,
        ]);
    }
}
