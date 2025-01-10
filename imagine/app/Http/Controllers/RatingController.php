<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rating;
use App\Models\PrintOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, User $user)
    {
        // Validate request
        $validated = $request->validate([
            'print_order_id' => 'required|exists:print_orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500'
        ]);

        // Check if user has already reviewed this order
        $existingRating = Rating::where('print_order_id', $validated['print_order_id'])
            ->where('rated_by', Auth::id())
            ->first();

        if ($existingRating) {
            return response()->json([
                'message' => 'You have already reviewed this order'
            ], 422);
        }

        // Check if print order belongs to seller and is completed
        $printOrder = PrintOrder::findOrFail($validated['print_order_id']);
        if ($printOrder->user_id !== $user->id || $printOrder->status !== 'completed') {
            return response()->json([
                'message' => 'Invalid print order'
            ], 422);
        }

        // Create rating
        $rating = Rating::create([
            'user_id' => $user->id,
            'rated_by' => Auth::id(),
            'print_order_id' => $validated['print_order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        // Load rater relationship for the response
        $rating->load('rater');

        return response()->json([
            'message' => 'Review submitted successfully',
            'rating' => $rating
        ]);
    }

    public function update(Request $request, User $user, Rating $rating)
    {
        // Check if user owns the rating
        if ($rating->rated_by !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Validate request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500'
        ]);

        // Update rating
        $rating->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'rating' => $rating
        ]);
    }

    public function destroy(User $user, Rating $rating)
    {
        // Check if user owns the rating
        if ($rating->rated_by !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $rating->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }
}
