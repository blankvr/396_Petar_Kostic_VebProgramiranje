<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Post;




class UserController extends Controller
{
    public function logout(){
        auth()->logout();
        return redirect('/login');
    }

    public function showCorrectHomepage(){
        
        $allPosts = Post::paginate(6);
        $allPosts = Post::orderBy('id', 'Desc')->paginate(6);

        if(auth()->check()){
            return view('home', ['posts' => $allPosts]);
            
        }
        else{
            return view('register');
        }
    }

    public function showMyFeed(){
        $user = auth()->user() ? auth()->user() : null;
        if($user instanceof User){
            return view('my-feed', ['posts' => $user->feedPosts()->latest()->paginate(6)]);
        }
    }


    public function registration(Request $request){
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
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }
    
    public function showEditProfileForm(User $user){
        return view('edit-profile', ['user' => $user]);
    }

    public function editProfile(User $user, Request $request){
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3'],
            'surname' => ['required', 'min:3'],
            'gender' => 'required',
            'dob' => 'required|date_format:Y-d-m|before:-13 years',
            'email' => ['required',  Rule::unique('users', 'email')->ignore(auth()->user()->email, 'email')],
            'username' => ['required', 'min:3', 'max:30', Rule::unique('users', 'username')->ignore(auth()->user()->username, 'username')],
            'password' => ['required', 'min:5', 'confirmed']
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user->update($incomingFields);
        
        auth()->logout();
        auth()->login($user);
        
        return redirect('/profile/'. auth()->user()->username);

    }

    
    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);
        
        if(auth()-> attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])){
            $request->session()->migrate();//$request->session()->regenerate()
            return redirect('/');
        }
        else{
            return redirect('/login');
        }
    }

    public function userProfile(User $user){
        
        $currentlyFollowing = 0;
        if(auth()->check()){
            $currentlyFollowing = Follow::where([['users_id', '=', auth()->user()->id], ['follows', '=', $user->id]])->count();
        }
        
        return view('user-profile', ['username' => $user->username,
        'name' => $user->name,
        'surname' => $user->surname,
        'gender' => $user->gender,
        'dob' => $user->dob,
        'role' => $user->role,
        'currentlyFollowing' => $currentlyFollowing,
        'posts' => $user->posts()->latest()->paginate(5),
        'followers' => $user->followers()->count(),
        'following' => $user->following()->count()]);
    }

    public function showFollowers(User $user){
        return view('user_followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function showFollowing(User $user){
        return view('user_following', ['following' => $user->following()->latest()->get()]);
    }

    public function deleteUser(User $user){
        if(auth()->user()->id === $user->id){
            //return "You can't delete your own account";
        }
        else{
            $user->delete();
            return back();
        }
    }
    public function deleteProfile(User $user){
        auth()->logout();
        $user->delete();
        return redirect('/');
        
    }
    public function editRole(User $user, Request $request){
        $role = $request->validate(['role' => 'required']);
        User::where('id', $user->id)->update(['role' => $role['role']]);
        return redirect('/manage-users');
    }
}
