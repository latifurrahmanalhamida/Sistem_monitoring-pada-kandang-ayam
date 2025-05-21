@extends('layouts.master')

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="my-4 ">
            <span class=" bg-danger text-white p-2 rounded rounded-lg">Monitoring Kandang Ayam Kecil</span>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (@isset($makan1))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Ketersediaan Pakan</p>
                            <p class="card-title text-center my-4">{{ $makan1['volume'] }} %</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (isset($minum1))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Volume Air 1</p>
                            <p class="card-title text-center my-4">{{$minum1['volume']  }} Ml</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (isset($suhu1))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Suhu Sensor 1</p>
                            <p class="card-title text-center my-4">{{ $suhu1 }} °C</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="my-4 ">
            <span class=" bg-danger text-white p-2 rounded rounded-lg">Monitoring Kandang Ayam Besar</span>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (@isset($makan3))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Ketersediaan Pakan</p>
                            <p class="card-title text-center my-4">{{ $makan3['volume'] }} %</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                    @if (isset($minum2))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Volume Air 2</p>
                            <p class="card-title text-center my-4">{{$minum2['volume']  }} Ml</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (isset($suhu2))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Suhu Sensor 2</p>
                            <p class="card-title text-center my-4">{{ $suhu2 }} °C</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="my-4 ">
            <span class=" bg-danger text-white p-2 rounded rounded-lg">Monitoring Kandang Ayam Sedang</span>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (@isset($makan2))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Ketersediaan Pakan</p>
                            <p class="card-title text-center my-4">{{ $makan2['volume'] }} %</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        @if (isset($suhu3))
                        <div class="numbers">
                            <p class="card-category text-left text-success font-weight-bold">Suhu Sensor 3</p>
                            <p class="card-title text-center my-4">{{ $suhu3 }} °C</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection