<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {

        $existCheck = Follow::where([['users_id', '=', auth()->user()->id], ['follows', '=', auth()->user()->id]])->count();

        if($existCheck){
            return 'error- already following that user';
        }

        $newFollow = new Follow;
        $newFollow->users_id = auth()->user()->id;
        $newFollow->follows = $user->id;
        $newFollow->save();

        return back();
    }

    public function removeFollow(User $user) {
        Follow::where([['users_id', '=', auth()->user()->id], ['follows', '=', $user->id]])->delete();
        return back();
    }
}
