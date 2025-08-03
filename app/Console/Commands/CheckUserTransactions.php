<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PointTransaction;

class CheckUserTransactions extends Command
{
    protected $signature = 'points:check-transactions {email}';
    protected $description = 'Kiểm tra giao dịch điểm của user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Không tìm thấy user với email: {$email}");
            return 1;
        }

        $this->info("Giao dịch điểm của user: {$user->name}");
        $this->info("=====================================");

        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get(['type', 'points', 'description', 'created_at']);

        if ($transactions->isEmpty()) {
            $this->info("Không có giao dịch nào.");
            return 0;
        }

        foreach ($transactions as $transaction) {
            $this->line("{$transaction->type}: {$transaction->points} - {$transaction->description} ({$transaction->created_at})");
        }

        return 0;
    }
} 