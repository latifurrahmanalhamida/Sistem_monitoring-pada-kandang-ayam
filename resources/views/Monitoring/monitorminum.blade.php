@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Minum</span>
    </div>
    <div>
        <h4>Air Minum</h4>
    </div>

    <!-- Formulir Filter -->
    <form method="GET" action="{{ route('showMonitoringMinum') }}">
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

    <!-- Data Minum 1 -->
    @if(isset($minum1))
    <div class="card mb-3">
        <h5 class="card-header">Minum 1</h5>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Volume</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $minum1['volume'] }} Ml </td>
                        @if ($minum1['status'] == "WATER EMPTY")
                        <td><span class="bg-danger text-white p-2">{{ $minum1['status'] }}</span></td>
                        @else
                        <td><span class="bg-success text-white p-2">{{ $minum1['status'] }}</span></td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Data Minum 2 -->
    @if(isset($minum2))
    <div class="card mb-3">
        <h5 class="card-header">Minum 2</h5>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Volume</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $minum2['volume'] }} Ml</td>
                        @if ($minum2['status'] == "WATER EMPTY")
                        <td><span class="bg-danger text-white p-2">{{ $minum2['status'] }}</span></td>
                        @else
                        <td><span class="bg-success text-white p-2">{{ $minum2['status'] }}</span></td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Chart untuk Data Terbaru dari Sensor1 -->
    @if(isset($latestSensorData['Sensor1']))
    <div class="card mb-4">
        <div class="card-header">Sensor 1</div>
        <div class="card-body">
            <canvas id="sensor1Chart" width="400" height="100"></canvas>
        </div>
    </div>
    @endif

    <!-- Chart untuk Data Terbaru dari Sensor2 -->
    @if(isset($latestSensorData['Sensor2']))
    <div class="card mb-4">
        <div class="card-header">Sensor 2</div>
        <div class="card-body">
            <canvas id="sensor2Chart" width="400" height="100"></canvas>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

@if(isset($latestSensorData['Sensor1']))
<script>
    var sensor1Data = @json($latestSensorData['Sensor1']);

    var sensor1Labels = sensor1Data.map(function(item) {
        return item.timestamp;
    });

    var sensor1Volume = sensor1Data.map(function(item) {
        return item.volume;
    });

    var ctxSensor1 = document.getElementById('sensor1Chart').getContext('2d');
    var sensor1Chart = new Chart(ctxSensor1, {
        type: 'line',
        data: {
            labels: sensor1Labels,
            datasets: [{
                label: 'Volume Sensor 1',
                data: sensor1Volume,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    },
                    title: {
                        display: true,
                        text: 'Timestamp'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Volume (%)'
                    }
                }
            }
        }
    });
</script>
@endif

@if(isset($latestSensorData['Sensor2']))
<script>
    var sensor2Data = @json($latestSensorData['Sensor2']);

    var sensor2Labels = sensor2Data.map(function(item) {
        return item.timestamp;
    });

    var sensor2Volume = sensor2Data.map(function(item) {
        return item.volume;
    });

    var ctxSensor2 = document.getElementById('sensor2Chart').getContext('2d');
    var sensor2Chart = new Chart(ctxSensor2, {
        type: 'line',
        data: {
            labels: sensor2Labels,
            datasets: [{
                label: 'Volume Sensor 2',
                data: sensor2Volume,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    },
                    title: {
                        display: true,
                        text: 'Timestamp'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Volume (%)'
                    }
                }
            }
        }
    });
</script>
@endif
@endsection