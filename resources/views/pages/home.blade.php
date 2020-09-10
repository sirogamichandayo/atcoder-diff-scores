@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('stricky-top')
    @parent
    {{-- search bar --}}
    <nav class="border-bottom navbar navbar-expand-md navbar-light bg-light">
        <form class="form-inline" method="post">
            {{ csrf_field() }}
            <input class="form-control mr-sm-2" type="text" 
                   value="{{$ids}}" placeholder="AtCoder ID, ..." 
                   aria-label="Search" name="ids">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>
@endsection

@section('content')
    <!-- Number -->
    <h1>Diff Sums</h1>
    <hr size=1>
    <div class="row">
        <div class="col-md-3 text-center">    
            <h6>sirogamichandayo</h6>
            <h3>500</h3>
            <h6 class="text-muted">2000rd</h6>
        </div>
        <div class="col-md-3 text-center">
            <h6>kurogamichandayo</h6>
            <h3>1000000</h3>
            <h6 class="text-muted">10rd</h6>
        </div>
    </div>
        
    <!-- Graphs -->
    <h1>Diff Graphs</h1>
    <hr size=1>
    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <canvas id="chart"></canvas>
    <script>
        (function() {
            'use strict';
            var type = 'line';
            var data = {
                labels: ['2018-01', '2018-02', '2018-03', '2018-04', '2018-04'],
                datasets: [{
                    label: 'type A',
                    data: [111, 222, 333, 444, 555]
                }, {
                    label: 'type B',
                    data: [555, 444, 333, 222, 111]
                }]
            };
        
            var options;
            var ctx = document.getElementById('chart');
            var myChart = new Chart(ctx, {
                type: type,
                data: data,
                options: options
            });
        })();
    </script>
@endsection
