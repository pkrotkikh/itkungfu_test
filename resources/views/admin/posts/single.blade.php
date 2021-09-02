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
                    <div class="text-center">
                        <h2>
                            Заголовок: {{ $post->title }}
                            <br><small>
                                Подзаголовок: {{ $post->subtitle }}
                            </small>
                        </h2>
                    </div>
                    <p>Post by {{ $post->user->name }}</p>
                    <p>{!! nl2br(e($post->body)) !!}</p>
                    <div class="row">
                        <div class="col-md-1">
                            <a href="{{ route('post.edit', $post->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                        <div class="col-md-1">
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['post.destroy', ['id' => $post->id]],
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-1">
                            Post is {{ $post->is_visible ? 'visible' : 'hidden' }}
                            {!! Form::open([
                                'method' => 'GET',
                                'route' => ['post.toggleVisible', ['id' => $post->id]],
                            ]) !!}
                            @if($post->is_visible)
                                {!! Form::submit('Hide post', ['class' => 'btn btn-sm btn-danger']) !!}
                            @else
                                {!! Form::submit('Show post', ['class' => 'btn btn-sm btn-danger']) !!}
                            @endif
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-9">
                            <p class="pull-right">Created at <b>{{ $post->created_at->toFormattedDateString() }}</b></p>
                        </div>

                        <a href="{{ route('dashboard') }}">Back to dashboard</a>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
