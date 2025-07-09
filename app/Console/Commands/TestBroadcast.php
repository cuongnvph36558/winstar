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
     */
    protected $signature = 'test:broadcast';

    /**
     * The console command description.
     */
    protected $description = 'Test broadcasting functionality for favorites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing broadcast functionality...');
        
        // Get first user and product
        $user = User::first();
        $product = Product::first();
        
        if (!$user || !$product) {
            $this->error('No user or product found. Please ensure you have data in your database.');
            return 1;
        }
        
        $this->info("Broadcasting test event for user: {$user->name} and product: {$product->name}");
        
        // Broadcast test event
        broadcast(new FavoriteUpdated($user, $product, 'added', 1));
        
        $this->info('Test broadcast sent successfully!');
        $this->info('Check your browser console and real-time notifications.');
        
        return 0;
    }
} 