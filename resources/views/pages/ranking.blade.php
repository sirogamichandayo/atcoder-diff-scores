@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('content')
    <h1>Ranking</h1>
    <hr size=1>

    <p>(大体一週間ぐらいかけて更新してます。気長にお待ちを...)</p>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>    

    <div class="row">    
        <div class="col-md-8 col-xs-8 col-sm-8 col-lg-8">
        </div>

        <div class="ranking search col-md-4 col-xs-4 col-sm-4 col-lg-4" style="mergin">
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

    <div class="row">
        <div class="col-md-6 col-xs-6  col-sm-6 col-lg-6" style="display: block;">
            <div class="pull-right pagination-link" >
                {{ $posts->links() }}    
            </div>
        </div>
    
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
            <div class="dropdown">
                <button class="float-right btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true">
                10
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
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
