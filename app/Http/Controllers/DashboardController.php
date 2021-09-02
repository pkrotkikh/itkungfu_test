<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\LoadAvatarRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        $posts = Post::paginate(config('paginate.posts'));
        return view('admin.dashboard', ['posts' => $posts]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', ['user' => $user]);
    }

    /**
     * @param LoadAvatarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loadAvatar(LoadAvatarRequest $request)
    {
        $file = $request->file('file');
        $fileName = Storage::disk('public')->put( 'avatars', $file);
        auth()->user()->update(['photo' => $fileName]);
        return back();
    }
}
