@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('stricky-top')
    @parent
    {{-- search bar --}}
    <nav class="border-bottom navbar navbar-expand-md navbar-light bg-light">
        <form class="form-inline" method="POST" action="/">
            @csrf
            <input class="form-control mr-sm-2" type="text" 
                   value="{{$raw_ids}}" placeholder="AtCoder ID, ..." 
                   aria-label="Search" name="raw_ids">
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit"></input>
        </form>
    </nav>
@endsection

@section('content')

    @isset($sum_data)
    <!-- Sum -->
    <h1>Diff Sums</h1>
    <hr size=1>
    <div class="row">
        @foreach($sum_data as $d)
        <div class="col-md-3 text-center">    
            <h6>{{ $d['id'] }}</h6>
            <h3>{{ $d['sum'] }}</h3>
            <h6 class="text-muted">
                {{ $d['rank'] }}
            </h6>
        </div>
        @endforeach
    </div>
    @endisset
        
    @isset($graph_data)    
    <!-- Graphs -->
    <h1>Diff Graphs</h1>
    <hr size=1>
    <!-- ChartJS -->
    <canvas id="chart"></canvas>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
    <script src="https://unpkg.com/chartjs-plugin-colorschemes"></script>

    <script>
        (function() {
            'use strict';

            var graph_data = @json($graph_data);
            console.log(graph_data);
            var type = 'line';
        
            var options = {
                plugins: {
                    scheme: 'brewer.Paired12'
                },
                scales: {
                    xAxes : [{
                        type: 'time',
                    }],
                    yAxes : [{
                        id: 'rate',
                        position: 'right',
                        beginAtZero: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Rating',
                            fontSize: 15,
                        }
                    }, {
                        id: 'diff',
                        position: 'left', 
                        beginAtZero: true,   
                        scaleLabel: {
                            display: true,    
                            labelString: 'Difficulty Sum',
                            fontSize: 15,
                        }
                    }] 
                }
            };

            var ctx = document.getElementById('chart');
            var myChart = new Chart(ctx, {
                type: type,
                data: graph_data,
                options: options
            });
        })();
    </script>
    @endisset
@endsection
