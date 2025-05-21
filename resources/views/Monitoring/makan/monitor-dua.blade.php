@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Makan</span>
    </div>
    <div>
        <h4>Ketersediaan Pakan Tangki {{ $feedVolume2 }}%</h4>
    </div>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Keluaran</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">TANGGAL DAN JAM</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feeds as $feed)
                <tr>
                    <td>{{ $feed['feed_volume_grams'] ?? '0' }} Gram</td>
                    <td>{{ $feed['time_of_day'] ?? '0' }} </td>
                    <td>{{ $feed['timestamp'] ?? '0' }} </td>
           
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <form action="{{ route('exportFeedingHistory2') }}" method="GET" class="form-inline my-3">
            <div class="form-group mx-sm-3 mb-2">
                <label for="month" class="sr-only">Bulan</label>
                <select name="month" id="month" class="form-control">
                    @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="year" class="sr-only">Tahun</label>
                <select name="year" id="year" class="form-control">
                    @foreach(range(date('Y') - 5, date('Y')) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Cetak</button>
        </form>
    </div>
</div>
@endsection
