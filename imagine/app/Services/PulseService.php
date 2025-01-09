<?php

namespace App\Services;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PulseService
{
    public function getCreditBalance(User $user): int
    {
        return $this->calculateBalanceFromDb($user);
    }

    public function addCredits(User $user, int $amount, ?string $description = null, ?string $reference = null): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive');
        }

        try {
            $transaction = CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'credit',
                'description' => $description,
                'reference' => $reference,
            ]);

            if (!$transaction) {
                throw new \Exception('Failed to create credit transaction');
            }
        } catch (\Exception $e) {
            Log::error('Failed to create credit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
            throw $e;
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
            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'description' => $description,
                'reference' => $reference,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create debit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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
}
