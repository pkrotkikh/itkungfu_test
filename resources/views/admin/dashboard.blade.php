<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Welcome!

                    <a class="btn btn-blue" href="{{route('post.create')}}">Create new post</a>
                </div>
            </div>
        </div>
    </div>

    @foreach($posts as $post)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <a href="{{route('post.show', $post->id)}}">{{$post->title}} / {{ $post->subtitle }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {!! $posts->links() !!}
</x-app-layout>
