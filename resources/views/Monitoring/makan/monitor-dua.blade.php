@extends('layouts.master')



@section('content')
<div class="content">
    <div class="my-4 ">
        <span class="bg-primary text-white p-2 rounded rounded-lg">Monitoring Makan</span>
    </div>
    <div>
        <h4>Sisa Pakan : {{ $feedVolume2 }}</h4>
    </div>
    <div class="card">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">Keluaran</th>
                <th scope="col">Jam</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>0 Gram</td>
                <td>07-05-2004</td>
              </tr>
            </tbody>
          </table>

    </div>


    <button type="button" class="btn btn-primary float-right">Cetak</button>
</div>
@endsection
