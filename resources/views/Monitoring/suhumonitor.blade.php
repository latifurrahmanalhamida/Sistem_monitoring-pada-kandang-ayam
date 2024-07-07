@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Suhu</span>
    </div>
    <div>
        <h5 class="card-title">Controlling Suhu</h5>
        <p class="text-right"><strong>Mode:</strong> {{ $controllingData['mode'] }}</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm">
                    <span>Kipas</span>
                    @if ($controllingData['kipas'] == "off")
                        <button class="btn btn-warning">{{ $controllingData['kipas'] }}</button>
                    @else
                        <button class="btn btn-success">{{ $controllingData['kipas'] }}</button>
                    @endif
                </div>
                <div class="col-sm">
                    <span>Lampu</span>
                    @if ($controllingData['lampu'] == "off")
                        <button class="btn btn-warning">{{ $controllingData['lampu'] }}</button>
                    @else
                        <button class="btn btn-success">{{ $controllingData['lampu'] }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Suhu</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Kelembapan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sensorData as $sensor)
                <tr>
                    <td>{{ $sensor['suhu'] }} °C</td>
                    <td>{{ $sensor['timestamp'] }}</td>
                    <td>{{ $sensor['kelembaban'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h5 class="card-title">Data Terbaru dari History Suhu</h5>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sensor</th>
                    <th scope="col">Suhu</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Kelembapan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($latestSensorData as $sensor)
                <tr>
                    <td>{{ $sensor['sensor'] }}</td>
                    <td>{{ $sensor['suhu'] }} °C</td>
                    <td>{{ $sensor['timestamp'] }}</td>
                    <td>{{ $sensor['kelembaban'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <canvas id="sensorChart" width="400" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var latestSensorData = @json($latestSensorData);

        var labels = latestSensorData.map(data => data.timestamp);
        var suhuData = latestSensorData.map(data => data.suhu);
        var kelembapanData = latestSensorData.map(data => data.kelembaban);

        var chartData = {
            labels: labels,
            datasets: [{
                label: 'Suhu',
                data: suhuData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Kelembapan',
                data: kelembapanData,
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 1
            }]
        };

        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        var ctx = document.getElementById('sensorChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: options
        });
    });
</script>
@endsection
