@extends('layouts.atcoder-dsum')

@section('title', 'AtCoder Diff Scores')

@section('stricky-top')
    @parent
    {{-- search bar --}}
    <nav class="border-bottom navbar navbar-expand-md navbar-light bg-light">
        <form class="form-inline", style="padding-left: 10px" method="POST" action="{{ route('home') }}">
            @csrf
            <input class="form-control mr-sm-2" type="text" 
                   value="{{old('raw_ids')}}" placeholder="AtCoder ID, ..." 
                   aria-label="Search" name="raw_ids">
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Search"></input>
        </form>
    </nav>
@endsection

@section('content')

    <!-- Sum -->
    <h1>Diff Sums</h1>
    <hr size=1>
    <div class="row">
        @foreach($sum_data as $d)
        @if ($d['id'] != '')
        <div class="col-md-3 text-center">    
            <h6>{{ $d['id'] }}</h6>
            <h3>{{ $d['sum'] }}</h3>
            <h6 class="text-muted">
                {{ $d['rank'] }}
            </h6>
        </div>
        @endif
        @endforeach
    </div>

        
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
                legend: {
                    labels: {
                        fontSize: 18,
                    },
                },
                plugins: {
                    scheme: 'brewer.Paired12'
                },
                scales: {
                    xAxes : [{
                        type: 'time',
                        ticks: {
                            fontSize: 15
                        }                        
                    }],
                    yAxes : [{
                        id: 'rate',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: 'Rating',
                            fontSize: 18,
                        },
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                        }
                    }, {
                        id: 'diff',
                        position: 'left', 
                        scaleLabel: {
                            display: true,    
                            labelString: 'Difficulty Sum',
                            fontSize: 18,
                        },
                        ticks: {
                            beginAtZero: true,
                            min: 0,
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

@endsection
