<?php

namespace App\Http\Controllers;


use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();
        if($user->hasRole('admin')) {
            $posts = Post::with(['user'])->paginate(config('paginate.posts'));
        } else {
            $posts = $user->posts()->with(['user'])->paginate(config('paginate.posts'));
        }
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePostRequest $request)
    {
        $user = auth()->user();
        DB::beginTransaction();
        try {
            $newPost = $request->all();
            $newPost['user_id'] = $user->id;
            Post::create($newPost);
            DB::commit();
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $postId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws HttpClientException
     * @throws \Exception
     */
    public function show($postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        if($user->can('view', $post)) {
            return view('admin.posts.single', compact('post'));
        } else {
            throw new \Exception('Access denied', 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws HttpClientException
     * @throws \Exception
     */
    public function edit($id)
    {
        $user = auth()->user();
        $post = Post::findOrFail($id);
        if($user->can('update', $post)) {
            return view('admin.posts.edit', compact('post'));
        } else {
            throw new \Exception('Access denied', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws HttpClientException
     * @throws \Exception
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $user = auth()->user();
        $post = Post::findOrFail($id);
        if($user->can('update', $post)) {
            DB::beginTransaction();
            try {
                $postData = $request->all();

                $post->update($postData);
                DB::commit();
                return redirect()->route('post.show', $post->id);
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
            }
        } else {
            throw new \Exception('Access denied', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws HttpClientException
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $post = Post::findOrFail($id);
        if($user->can('delete', $post)) {
            DB::beginTransaction();
            try {
                $post->delete();
                DB::commit();
                return redirect()->route('dashboard');
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
            }
        } else {
            throw new \Exception('Access denied', 403);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws HttpClientException
     * @throws \Exception
     */
    public function delete($id)
    {
        $user = auth()->user();
        $post = Post::withTrashed()->findOrFail($id);
        if($user->can('delete', $post)) {
            DB::beginTransaction();
            try {
                $post->forceDelete();
                DB::commit();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
            }
        } else {
            throw new \Exception('Access denied', 403);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws HttpClientException
     * @throws \Exception
     */
    public function toggleVisible($id)
    {
        $user = auth()->user();
        /** @var Post $post */
        $post = Post::findOrFail($id);
        if($user->can('toggleVisible', $post)) {
            DB::beginTransaction();
            try {
                if ($post->is_visible) {
                    $post->update(['is_visible' => false]);
                } else {
                    $post->update(['is_visible' => true]);
                }
                DB::commit();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
            }
        } else {
            throw new \Exception('Access denied', 403);
        }
    }
}
