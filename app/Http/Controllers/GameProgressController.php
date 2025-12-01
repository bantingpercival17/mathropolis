<?php

namespace App\Http\Controllers;

use App\Models\GameProgress;
use Illuminate\Http\Request;

class GameProgressController extends Controller
{
    function progress()
    {
        try {
            $user = auth()->user();
            $progress = GameProgress::where('user_id', $user->id)->first();
            return response()->json([
                'progress' => $progress ? $progress->progress : 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving progress', 'error' => $e->getMessage()], 500);
        }
    }

    function updateProgress(Request $request)
    {
        try {
            $user = auth()->user();
            $validated = $request->validate([
                'coins' => 'sometimes|integer|min:0',
                'budget_plan' => 'sometimes|json',
                'progress_data' => 'sometimes|json',
            ]);

            $progress = GameProgress::updateOrCreate(
                ['user_id' => $user->id],
                ['coins' => 0, 'progress_data' => $validated['progress_data'], 'budget_plan' => $validated['budget_plan']],
            );

            return response()->json([
                'message' => 'Progress updated successfully',
                'progress' => $progress->progress,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating progress', 'error' => $e->getMessage()], 500);
        }
    }
}
