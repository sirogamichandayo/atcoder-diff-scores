@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('content')
    <h1>Ranking</h1>
    <hr size=1>

    <p>(大体一週間ぐらいかけて更新してます。気長にお待ちを...)</p>

    <div class="my-ranking-row-1" style="margin-bottom:15px">
        <div class="row">        
            <div class="col-md-5 col-xs-5 col-sm-5 col-lg-5" style="mergin">
                <form action="{{ route('ranking') }}" method="POST" role="search">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control" name="id"
                            placeholder="AtCoder ID" value="{{ old('id') }}"> 
                        <span class="input-group-btn"></span>
                    </div>
                </form>    
            </div>
        </div>
    </div>

    <div class="my-ranking-row-2" >
        <div class="row">    
            <div class="col-md-12 col-xs-12  col-sm-12 col-lg-12" style="display: block;">
                <div class="pull-right pagination-link" >
                    {{ $posts->links() }}    
                </div>
            </div>
        </div>    
    </div>    

    @isset($posts)    
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">Rank</th>
                <th scope="col">Id</th>
                <th scope="col">Diff Sum</th>
            </tr>
        </thead>

        <tbody>        
            @foreach ($posts as $post_key => $post)
            <tr>
                <td>{{ $post->get_rank() }}</td>
                <td>{{ $post->user_id }}</td>
                <td>{{ number_format($post->diff_sum) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset
@endsection
