<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'voteable_type' => 'required|in:post,comment',
            'voteable_id' => 'required|integer',
            'value' => 'required|in:1,-1',
        ]);

        $modelClass = $validated['voteable_type'] === 'post'
            ? \App\Models\Post::class
            : \App\Models\Comment::class;

        $model = $modelClass::findOrFail($validated['voteable_id']);

        $existing = Vote::where('user_id', auth()->id())
            ->where('voteable_type', $modelClass)
            ->where('voteable_id', $model->id)
            ->first();

        $userVote = null;

        if ($existing) {
            if ($existing->value == $validated['value']) {
                $existing->delete(); // Toggle off
            } else {
                $existing->update(['value' => $validated['value']]);
                $userVote = (int) $validated['value'];
            }
        } else {
            Vote::create([
                'user_id' => auth()->id(),
                'voteable_type' => $modelClass,
                'voteable_id' => $model->id,
                'value' => $validated['value'],
            ]);
            $userVote = (int) $validated['value'];
        }

        $score = Vote::where('voteable_type', $modelClass)
            ->where('voteable_id', $model->id)
            ->sum('value');

        return response()->json([
            'score' => $score,
            'user_vote' => $userVote,
        ]);
    }
}
