<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function register(){
        return view('register');
    }

    public function login(){
        return view('login');
    }
    public function home(){
        return view('home');
    }
    public function managePosts(){
        $allPosts = Post::all();
        return view('manage-posts', ['posts' => $allPosts]);
    }
    public function manageUsers(){
        $allUsers = User::all();
        return view('manage-users', ['users' => $allUsers]);
    }
    public function adminPage(){
        return view('admin-page');
    }
    public function editUserRole(User $user){
        return view('edit-user-role', ['user' => $user]);
    }
}
