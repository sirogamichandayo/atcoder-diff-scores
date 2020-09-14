@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('content')
    <h1>Ranking</h1>
    <hr size=1>

    <div class="d-flex">
        {{ $posts->links() }}    
    </div>
    <table class="table table-striped">
        <tr>
            <th>Rank</th>
            <th>Id</th>
            <th>Diff Sum</th>
        </tr>

        @foreach ($posts as $post)
        <tr>
            <td>{{ $posts->firstItem() + $loop->index}}</td>
            <td>{{ $post->user_id }}</td>
            <td>{{ number_format($post->diff_sum) }}</td>
        </tr>
        @endforeach

    </table>
@endsection
