<?php

namespace App\Http\Controllers;

use App\Models\GameProgress;
use App\Models\User;
use Illuminate\Http\Request;

class GameProgressController extends Controller
{
    function progress()
    {
        try {
            $user = auth()->user();
            $progress = GameProgress::where('user_id', $user->id)->first();
            return response()->json([
                'progress' => $progress ? $progress : 0,
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
            $accountCheck = GameProgress::where('user_id', $user->id)->first();
            if (!$accountCheck) {
                // Create new record if not exists
                $accountCheck = new GameProgress();
                $accountCheck->user_id = $user->id;
                $accountCheck->coins = $validated['coins'];
                $accountCheck->progress_data = $validated['progress_data'];
                $accountCheck->budget_plan = $validated['budget_plan'];
                $accountCheck->save();
            }
            $progress = GameProgress::updateOrCreate(
                ['user_id' => $user->id],
                ['coins' => $validated['coins'], 'progress_data' => $validated['progress_data'], 'budget_plan' => $validated['budget_plan']],
            );

            return response()->json([
                'message' => 'Progress updated successfully',
                'progress' => $progress,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating progress', 'error' => $e->getMessage()], 500);
        }
    }

    function studentsProgress()
    {
        try {
            $teacher = auth()->user();
            $studentsProgress = User::where('teacherCode', $teacher->teacherCode)
                ->where('id', '!=', $teacher->id)
                ->get();
            $progressData = [];
            foreach ($studentsProgress as $key => $value) {
                $unlocked = null;

                foreach (json_decode($value->gameProgress->progress_data, true) as $item) {
                    if (isset($item['isLocked']) && $item['isLocked'] === false) {
                        $unlocked = $item;
                    }
                }

                $progressData[] = array(
                    'id' => base64_encode($value->id),
                    'name' => $value->name,
                    'progress' => $value->gameProgress ? $unlocked : null,
                    'budget_plan' => $value->gameProgress ? json_decode($value->gameProgress->budget_plan) : null,
                    'coins' => $value->gameProgress ? $value->gameProgress->coins : null,
                );
            }
            return response()->json([
                'students_progress' => $progressData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving students progress', 'error' => $e->getMessage()], 500);
        }
    }
}
