<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    function registration(Request $request){
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3'],
            'surname' => ['required', 'min:3'],
            'gender' => 'required',
            'dob' => 'required|date_format:Y-d-m|before:-13 years',
            'email' => ['required', Rule::unique('users', 'email')],
            'username' => ['required', 'min:3', 'max:30',  Rule::unique('users', 'username')],
            'password' => ['required', 'min:5', 'confirmed'],

        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);
        return "test";
    }
}
