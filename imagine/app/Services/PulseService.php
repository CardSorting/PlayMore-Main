<?php

namespace App\Services;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Facades\{DB, Log};

class PulseService
{
    public function getCreditBalance(User $user): int
    {
        return $this->calculateBalanceFromDb($user);
    }

    public function addCredits(User $user, int $amount, ?string $description = null, ?string $reference = null): void
    {
        Log::info('Starting addCredits process', [
            'user_id' => $user->id,
            'amount' => $amount,
            'description' => $description,
            'reference' => $reference
        ]);

        if ($amount <= 0) {
            Log::error('Invalid credit amount', ['amount' => $amount]);
            throw new \InvalidArgumentException('Credit amount must be positive');
        }

        try {
            DB::beginTransaction();

            Log::info('Creating credit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'credit'
            ]);

            $transaction = CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'credit',
                'description' => $description,
                'reference' => $reference,
            ]);

            if (!$transaction) {
                Log::error('Transaction creation returned null');
                DB::rollBack();
                throw new \Exception('Failed to create credit transaction');
            }

            Log::info('Credit transaction created successfully', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $amount
            ]);

            // Verify the transaction was saved
            $savedTransaction = CreditTransaction::find($transaction->id);
            if (!$savedTransaction) {
                Log::error('Transaction not found after creation', [
                    'transaction_id' => $transaction->id
                ]);
                DB::rollBack();
                throw new \Exception('Transaction not found after creation');
            }

            Log::info('Credit transaction verified', [
                'transaction_id' => $savedTransaction->id,
                'amount' => $savedTransaction->amount,
                'type' => $savedTransaction->type
            ]);

            DB::commit();

            Log::info('Transaction committed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create credit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'reference' => $reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deductCredits(User $user, int $amount, ?string $description = null, ?string $reference = null): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be positive');
        }

        try {
            DB::beginTransaction();

            $currentBalance = $this->getCreditBalance($user);

            if ($currentBalance < $amount) {
                DB::rollBack();
                return false;
            }

            Log::info('Creating debit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit'
            ]);

            $transaction = CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'description' => $description,
                'reference' => $reference,
            ]);

            if (!$transaction) {
                Log::error('Debit transaction creation returned null');
                DB::rollBack();
                throw new \Exception('Failed to create debit transaction');
            }

            Log::info('Debit transaction created successfully', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $amount
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create debit transaction', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'reference' => $reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
