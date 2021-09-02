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
                    @include('layouts.errors')
                    Welcome!

                    <a class="btn btn-blue" href="{{route('post.create')}}">Create new post</a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>Name: {{ $user->name }}</p>
                    <p>Email: {{ $user->email }}</p>
                    <p>Avatar: <img src="{{ asset('storage/' . $user->photo) }}" width="100"/></p>
                    {!! Form::open([
                       'method' => 'POST',
                       'route' => ['loadAvatar'],
                       'files'=>'true'
                    ]) !!}
                    {!! Form::file('file') !!}
                    {!! Form::submit('Load avatar', ['class' => 'btn btn-sm btn-danger']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
