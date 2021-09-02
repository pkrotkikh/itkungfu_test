<?php

namespace App\Http\Controllers;

use App\Models\Post;

class MainPageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Post::visible()
            ->paginate(config('paginate.posts'));
        return view('welcome', ['posts' => $posts]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $post = Post::visible()->where('id', $id)->first();
        return view('posts.single')->with(compact('post'));
    }
}
