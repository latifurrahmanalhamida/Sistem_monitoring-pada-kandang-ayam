<!-- resources/views/your-view.blade.php -->
@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Suhu</span>
    </div>
    <!-- Formulir Filter -->
    <form method="GET" action="{{ route('getDataSuhu') }}">
        <div class="form-group">
            <label for="selected_year">Tahun:</label>
            <select class="form-control" id="selected_year" name="selected_year">
                <option value="">Pilih Tahun</option>
                @for ($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label for="selected_month">Bulan:</label>
            <select class="form-control" id="selected_month" name="selected_month">
                <option value="">Pilih Bulan</option>
                @for ($month = 1; $month <= 12; $month++)
                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ str_pad($month, 2, '0', STR_PAD_LEFT) == $selectedMonth ? 'selected' : '' }}>
                        {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label for="selected_day">Hari:</label>
            <select class="form-control" id="selected_day" name="selected_day">
                <option value="">Pilih Hari</option>
                @for ($day = 1; $day <= 31; $day++)
                    <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ str_pad($day, 2, '0', STR_PAD_LEFT) == $selectedDay ? 'selected' : '' }}>
                        {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Sensor Data -->
    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered table-hover table-responsive table-full-width table-custom">
                <thead class="thead-dark">
                    <tr>
                        <th>Category</th>
                        <th>Kelembaban</th>
                        <th>Suhu</th>
                        <th>Timestamp</th>
                        <th>Kipas</th>
                        <th>Lampu</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sensorData as $data)
                    <tr>
                        <td>{{ $data['category'] }}</td>
                        <td>{{ $data['kelembaban'] }}%</td>
                        <td>{{ $data['suhu'] }}°C</td>
                        <td>{{ $data['timestamp'] }}</td>
                        <td>{{ $data['kipas'] }}</td>
                        <td>{{ $data['lampu'] }}</td>
                        <td>{{ $data['mode'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart untuk Setiap Sensor -->
    @foreach ($latestSensorData as $sensorId => $sensorData)
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Data Terbaru dari Sensor {{ $sensorId }}</h5>
            <canvas id="sensorChart{{ $sensorId }}" width="400" height="100"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sensorData{{ $sensorId }} = @json($sensorData);

            var labels{{ $sensorId }} = sensorData{{ $sensorId }}.map(data => data.timestamp);
            var suhuData{{ $sensorId }} = sensorData{{ $sensorId }}.map(data => data.suhu);
            var kelembapanData{{ $sensorId }} = sensorData{{ $sensorId }}.map(data => data.kelembaban);

            var chartData{{ $sensorId }} = {
                labels: labels{{ $sensorId }},
                datasets: [{
                    label: 'Suhu',
                    data: suhuData{{ $sensorId }},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Kelembapan',
                    data: kelembapanData{{ $sensorId }},
                    backgroundColor: 'rgba(46, 204, 113, 0.2)',
                    borderColor: 'rgba(46, 204, 113, 1)',
                    borderWidth: 1
                }]
            };

            var options{{ $sensorId }} = {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var ctx{{ $sensorId }} = document.getElementById('sensorChart{{ $sensorId }}').getContext('2d');
            var myChart{{ $sensorId }} = new Chart(ctx{{ $sensorId }}, {
                type: 'line',
                data: chartData{{ $sensorId }},
                options: options{{ $sensorId }}
            });
        });
    </script>
    @endforeach
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