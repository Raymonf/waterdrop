<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function vote(Request $request, string $type)
    {
        if (!array_key_exists($type, Vote::VOTABLES)) {
            abort(400);
        }

        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer|min:-1|max:1',
        ]);

        $status = intval($request->status);
        $class = Vote::VOTABLES[$type];
        $model = (new $class())->find($request->id);

        if ($model) {
            // TODO: use save(), locking
            DB::transaction(function () use ($model, $status) {
                // Remove old votes
                Vote::where('votable_id', $model->id)
                    ->where('votable_type', get_class($model))
                    ->where('user_id', auth()->id())
                    ->delete();

                if ($status != 0) {
                    Vote::create([
                        'votable_id' => $model->id,
                        'votable_type' => get_class($model),
                        'user_id' => auth()->id(),
                        'status' => $status
                    ]);
                }
            });

            return $status;
        }

        abort(400);
    }
}
