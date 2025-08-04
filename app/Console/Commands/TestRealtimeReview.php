<?php

namespace App\Console\Commands;

use App\Events\NewReviewAdded;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Console\Command;

class TestRealtimeReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:realtime-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test realtime notification for new review';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing realtime notification for new review...');

        // Get first user and product for testing
        $user = User::first();
        $product = Product::first();

        if (!$user || !$product) {
            $this->error('No user or product found for testing');
            return 1;
        }

        // Create a test review
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'name' => $user->name,
            'email' => $user->email,
            'rating' => 5,
            'content' => 'Đây là đánh giá test realtime - ' . now()->format('H:i:s'),
            'status' => 1,
        ]);

        // Load relationships
        $review->load(['user', 'product']);

        $this->info("Review ID: {$review->id}");
        $this->info("User: {$user->name}");
        $this->info("Product: {$product->name}");
        $this->info("Rating: {$review->rating} stars");
        $this->info("Content: {$review->content}");

        // Dispatch event
        try {
            event(new NewReviewAdded($review));
            $this->info('✅ NewReviewAdded event triggered successfully!');
            $this->info('📡 Check admin page to see realtime notification');
        } catch (\Exception $e) {
            $this->error('❌ Failed to trigger event: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
