<?php

namespace App\Http\Controllers;

use App\Models\GameProgress;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function getProgress(Request $request)
    {
        return $request->user()->gameProgress;
    }

    public function saveProgress(Request $request)
    {
        $progress = GameProgress::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['progress_data' => json_encode($request->progress)]
        );
        return response()->json($progress);
    }
}
