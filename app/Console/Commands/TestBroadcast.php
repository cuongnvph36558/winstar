<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\FavoriteUpdated;
use App\Models\User;
use App\Models\Product;

class TestBroadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test broadcasting functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing broadcast functionality...');
        
        try {
            // Get a test user and product
            $user = User::first();
            $product = Product::first();
            
            if (!$user || !$product) {
                $this->error('No users or products found in database');
                return 1;
            }
            
            $this->info("Broadcasting test event for user: {$user->name}, product: {$product->name}");
            
            // Broadcast test event
            broadcast(new FavoriteUpdated($user, $product, 'added', 1));
            
            $this->info('✅ Broadcast event sent successfully!');
            $this->info('Check your browser console for realtime updates');
            
        } catch (\Exception $e) {
            $this->error('❌ Broadcast failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
} 