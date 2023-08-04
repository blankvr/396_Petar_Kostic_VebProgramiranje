<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm(){
        return view('create-post');
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['users_id'] = auth()->id();

        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}");
    }

    public function viewSinglePost(Post $post){
        return view('single-post', ['post' => $post]);
    }

    public function delete(Request $request, Post $post){
        if($request->user()->cannot('delete', $post)){
            return 'Error';
        }
        $post->delete();
        return redirect('/profile/'. auth()->user()->username);
    }
    public function adminDelete(Post $post){
        $post->delete();
        return back();
    }

    public function showEditForm(Post $post){
        return view('edit-post', ['post' => $post]);
    }

    public function edit(Post $post, Request $request){
        $incomingFields = $request->validate([
            'title' => 'required', 
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return redirect('/post/'. $post['id']);
    }

}
