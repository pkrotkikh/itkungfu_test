<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.errors')
                <a href="{{ route('post.create') }}" class="btn btn-success">New</a>
                @foreach ($posts as $p)
                    <hr>
                    <div class="text-center">
                        <p class="text-uppercase">
                            @foreach($p->categories as $c)
                                {{ $c->title }}
                            @endforeach
                        </p>
                        <h2>
                            <a href="{{ route('post.show', $p->id) }}">{{ $p->title }}</a>
                            <br><small>
                                {{ $p->subtitle }}
                            </small>
                        </h2>
                    </div>
                    <p>{!! nl2br(e($p->body)) !!}</p>
                    <div class="row">
                        <div class="col-md-1">
                            <a href="{{ route('post.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                        <div class="col-md-1">
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['post.destroy', $p->id]
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-10">
                            <p class="pull-right">Created at <b>{{ $p->created_at->toFormattedDateString() }}</b></p>
                        </div>
                    </div>
                @endforeach

                AAAAAAAA

                {!! $posts->links() !!}

            </div>
        </div>
    </div>
</x-app-layout>
