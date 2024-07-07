@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Minum</span>
    </div>
    <div>
        <h4>Air Minum</h4>
    </div>

    @if(isset($minum1))
    <div class="card mb-3">
        <h5 class="card-header">Minum 1</h5>
        <div class="card-body">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Keluaran</th>
                    <th scope="col">Jam</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ round($minum1['volume'], ) }} %</td>
                    <td>00:00</td>
                    @if ($minum1['status'] == "WATER EMPTY")
                    <td ><span class="bg-danger text-white p-2">{{ $minum1['status'] }}</span> </td>
                    @else
                    <td ><span class="bg-success text-white p-2">{{ $minum1['status'] }}</span> </td>
                    @endif
                  </tr>
                </tbody>
              </table>


        </div>
    </div>
    @endif

    @if(isset($minum2))
    <div class="card mb-3">
        <h5 class="card-header">Minum 2</h5>
        <div class="card-body">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Keluaran</th>
                    <th scope="col">Jam</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ round($minum2['volume'], ) }} %</td>
                    <td>00:00</td>

                    @if ($minum2['status'] == "WATER EMPTY")
                    <td ><span class="bg-danger text-white p-2">{{ $minum2['status'] }}</span> </td>
                    @else
                    <td ><span class="bg-success text-white p-2">{{ $minum2['status'] }}</span> </td>
                    @endif
                  </tr>
                </tbody>
              </table>
        </div>
    </div>
    @endif
</div>
@endsection
