@extends('layouts.app')




@section('content')

<div class="d-flex justify-content-end mb-2">
<a href="{{ route('posts.create') }}" class="btn btn-success">Add Post</a>
</div>

<div class="card card-default">
    <div class="card-header">
        Posts
    </div>
    <div class="card-body">
        @if($posts->isNotEmpty())

        <table class="table">
            <thead>
                <th>Image</th>
                <th>Title</th>
            </thead>

            @foreach($posts as $post)
            <tr>
                <td><img src="{{ asset('storage/'.$post->image) }}" width="120" height="60" alt="{{$post->title}}"></td>
                <td>{{$post->title}}</td>
                @if(!$post->trashed())
                <td><a href="{{route('posts.edit', $post->id )}}" class="btn btn-info btn-sm">Edit</a></td>
                @endif
                <td>
                <form action="{{route('posts.destroy', $post->id )}}" method="post">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" >
                    {{$post->trashed()?'Delete' : 'Trash'}}
                </button>
                </form>
                </td>
            </tr>
            @endforeach

        </table>

        @else

        <h3 class="text-center">No Posts Yet.</h3>

        @endif

    </div>
    </div>

@endsection


