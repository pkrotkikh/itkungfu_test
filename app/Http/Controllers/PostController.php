<?php

namespace App\Http\Controllers;


use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /** @var User|null $user */
    protected $user;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        if($this->user->hasRole('admin')) {
            $posts = Post::with(['user'])->paginate(config('paginate.posts'));
        } else {
            $posts = $this->user->posts()->with(['user'])->paginate(config('paginate.posts'));
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
        DB::beginTransaction();
        try {
            $newPost = $request->all();
            $newPost['user_id'] = $this->user->id;
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
        $post = Post::findOrFail($postId);
        if($this->user->can('view', $post)) {
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
        $post = Post::findOrFail($id);
        if($this->user->can('update', $post)) {
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
        $post = Post::findOrFail($id);
        if($this->user->can('update', $post)) {
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
        $post = Post::findOrFail($id);
        if($this->user->can('delete', $post)) {
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
        $post = Post::withTrashed()->findOrFail($id);
        if($this->user->can('delete', $post)) {
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
        /** @var Post $post */
        $post = Post::findOrFail($id);
        if($this->user->can('toggleVisible', $post)) {
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
