@extends('layouts.master')

@section('content')
<div class="content">
    <div class="my-4">
        <a class="btn btn-default" href="/create-user">Tambah Data Karyawan</a>
    </div>
    <div>
        <h4>Data Pegawai</h4>
    </div>
    <div class="card">
        @if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        <span>{{ $error }}</span>
    @endforeach
</div>
@endif

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif
        @if (@isset($users) && count($users) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Email</th>
                    <th scope="col">Roles</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $id => $user)
                <tr>
                    <th scope="col">{{ $loop->iteration }}</th>
                    <th scope="col">{{ $user['name'] }}</th>
                    <th scope="col">{{ isset($user['email']) ? htmlspecialchars($user['email']) : '' }}</th>
                    <th scope="col">{{ isset($user['roles']) ? htmlspecialchars($user['roles']) : '' }}</th>
                    <th scope="col">
                        <a href="{{ route('user.edit', $id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('user.delete', $id) }}" method="GET" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>No User found</p>
    @endif
</div>
@endsection
