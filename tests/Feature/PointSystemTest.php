<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PointSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $pointService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pointService = new PointService();
    }

    /** @test */
    public function test_one_point_equals_one_vnd()
    {
        // Test quy tắc cơ bản: 1 điểm = 1 VND
        $points = 1000;
        $expectedValue = 1000; // 1000 VND
        
        $actualValue = $this->pointService->calculatePointsValue($points);
        
        $this->assertEquals($expectedValue, $actualValue);
    }

    /** @test */
    public function test_points_needed_for_money_amount()
    {
        // Test tính số điểm cần để đổi thành tiền
        $moneyAmount = 5000; // 5000 VND
        $expectedPoints = 5000; // Cần 5000 điểm
        
        $actualPoints = $this->pointService->calculatePointsNeeded($moneyAmount);
        
        $this->assertEquals($expectedPoints, $actualPoints);
    }

    /** @test */
    public function test_return_exchange_points_rule()
    {
        // Test quy tắc hoàn hàng đổi điểm: số điểm hoàn = số tiền hoàn
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total_amount' => 10000, // 10,000 VND
            'return_method' => 'points',
            'return_amount' => 5000, // 5,000 VND
        ]);

        // Tạo point record cho user
        $point = Point::create([
            'user_id' => $user->id,
            'total_points' => 0,
            'used_points' => 0,
        ]);

        // Giả lập việc cộng điểm từ hoàn hàng
        $pointsToAdd = $order->return_amount; // 5000 điểm
        $point->total_points += $pointsToAdd;
        $point->save();

        // Kiểm tra điểm đã được cộng đúng
        $this->assertEquals(5000, $point->total_points);
        
        // Kiểm tra giá trị tiền của điểm
        $pointsValue = $this->pointService->calculatePointsValue($point->total_points);
        $this->assertEquals(5000, $pointsValue); // 5000 VND
    }

    /** @test */
    public function test_use_points_for_order()
    {
        // Test sử dụng điểm để giảm giá đơn hàng
        $user = User::factory()->create();
        $point = Point::create([
            'user_id' => $user->id,
            'total_points' => 10000, // 10,000 điểm
            'used_points' => 0,
        ]);

        $orderTotal = 15000; // 15,000 VND
        $pointsToUse = 5000; // 5,000 điểm

        $result = $this->pointService->usePointsForOrder($user, $pointsToUse, $orderTotal);

        $this->assertTrue($result['success']);
        $this->assertEquals(5000, $result['points_used']);
        $this->assertEquals(5000, $result['points_value']); // 5,000 VND
        $this->assertEquals(5000, $result['remaining_points']); // 10,000 - 5,000 = 5,000
    }

    /** @test */
    public function test_points_cannot_exceed_order_total()
    {
        // Test không thể sử dụng điểm vượt quá 100% giá trị đơn hàng
        $user = User::factory()->create();
        $point = Point::create([
            'user_id' => $user->id,
            'total_points' => 20000, // 20,000 điểm
            'used_points' => 0,
        ]);

        $orderTotal = 10000; // 10,000 VND
        $pointsToUse = 15000; // 15,000 điểm (vượt quá 100%)

        $result = $this->pointService->usePointsForOrder($user, $pointsToUse, $orderTotal);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('tối đa', $result['message']);
    }

    /** @test */
    public function test_earn_points_from_order()
    {
        // Test tích điểm từ đơn hàng (1% giá trị đơn hàng)
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total_amount' => 100000, // 100,000 VND
            'status' => 'completed',
        ]);

        $result = $this->pointService->earnPointsFromOrder($user, $order);

        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertNotNull($user->point);
        $this->assertEquals(1000, $user->point->total_points); // 100,000 × 1% = 1,000 điểm
    }
}
