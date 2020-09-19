@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('content')
    <h1>API</h1>
    <hr size=1>
    <p>AtCoder ProblemsのDifficultyに関する非公式APIです。<br></p>

    <h3>注意</h3>
    <hr size=1>
    <p>
    頻繁にアクセスするのは遠慮ください。
    <b>必ず</b>一秒のディレイをかけてアクセスしてください。
    でないと中の人の借金が増えちゃいますw
    <br>
    </p>

    <h3>Info</h3>
    <hr size=1>
    <h5>Diff Sum Per Date</h5>
    <p>日付でソートされてます</p>
    <p><b>Interface</b></p>
    <pre><code>{{$request->getUriForPath('') . '/api/resources/diff_per_date?user_id={user_id}'}};</code></pre>
    <p><b>Example</b></p>
    <ul><li>
        <a href="{{$request->getUriForPath('') . '/api/resources/diff_per_date?user_id=sirogamichandayo'}}">
        {{$request->getUriForPath('') . '/api/resources/diff_per_date?user_id=sirogamichandayo'}}    
        </a>    
    </li></ul>
@endsection