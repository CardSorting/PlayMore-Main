<?php

namespace App\Services;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;

class PulseService
{
    private const CREDIT_KEY_PREFIX = 'user_credits:';
    private Connection $redis;

    public function __construct(RedisManager $redis)
    {
        $this->redis = $redis->connection();
    }

    public function getCreditBalance(User $user): int
    {
        try {
            $balance = $this->redis->get($this->getCreditKey($user->id));
        
            if ($balance === null) {
                // If not in Redis, calculate from transactions and cache it
                $balance = $this->calculateAndCacheBalance($user);
            }

            return (int) $balance;
        } catch (\Exception $e) {
            Log::error('Redis error: ' . $e->getMessage());
            // Fallback to calculating from database if Redis fails
            return $this->calculateBalanceFromDb($user);
        }
    }

    public function addCredits(User $user, int $amount, ?string $description = null, ?string $reference = null): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive');
        }

        // First create the transaction record in the database
        $transaction = CreditTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
            'reference' => $reference,
        ]);

        if (!$transaction) {
            Log::error('Failed to create credit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'reference' => $reference
            ]);
            throw new \Exception('Failed to create credit transaction');
        }

        // Then try to update Redis, but don't let Redis failures affect the transaction
        try {
            $this->redis->incrby($this->getCreditKey($user->id), $amount);
        } catch (\Exception $e) {
            Log::error('Redis error in addCredits - credits added to database but Redis update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'amount' => $amount,
                'transaction_id' => $transaction->id
            ]);
            // Redis failed but database transaction succeeded, so we can continue
        }
    }

    public function deductCredits(User $user, int $amount, ?string $description = null, ?string $reference = null): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be positive');
        }

        $currentBalance = $this->getCreditBalance($user);

        if ($currentBalance < $amount) {
            return false;
        }

        try {
            // Create transaction record
            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'description' => $description,
                'reference' => $reference,
            ]);

            // Update Redis
            $this->redis->decrby($this->getCreditKey($user->id), $amount);
        } catch (\Exception $e) {
            Log::error('Redis error in deductCredits: ' . $e->getMessage());
            // Continue since the database transaction was successful
        }

        return true;
    }

    public function getTransactionHistory(User $user, int $limit = 10)
    {
        return CreditTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function calculateBalanceFromDb(User $user): int
    {
        $credits = CreditTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->sum('amount');

        $debits = CreditTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->sum('amount');

        return $credits - $debits;
    }

    private function calculateAndCacheBalance(User $user): int
    {
        $balance = $this->calculateBalanceFromDb($user);

        try {
            // Cache the balance in Redis
            $this->redis->set($this->getCreditKey($user->id), $balance);
        } catch (\Exception $e) {
            Log::error('Redis error in calculateAndCacheBalance: ' . $e->getMessage());
        }

        return $balance;
    }

    private function getCreditKey(int $userId): string
    {
        return self::CREDIT_KEY_PREFIX . $userId;
    }
}
