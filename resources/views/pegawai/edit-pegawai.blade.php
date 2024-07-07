@extends('layouts.master')

@section('content')

<div class="content">

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

<!-- Update User Form -->
<form action="{{ route('user.update', $id) }}" method="POST">
@csrf
@method('PATCH')
<div class="my-3">
    <label for="email">Email:</label>
    <input class="form-control" type="email" id="email" name="email" value="{{ $user['email'] }}" required>
</div>
<div class="my-3">
    <label for="password">Password:</label>
    <input class="form-control" type="password" id="password" name="password" value="{{ $user['password'] }}" required>
</div>
<div class="my-3">
    <label for="name">Name:</label>
    <input class="form-control" type="text" id="name" name="name" value="{{ $user['name'] }}" required>
</div>
<div class="my-3">
    <label for="roles">Roles:</label>
    <select id="roles" name="roles" class="custom-select">
        <option value="admin" {{ $user['roles'] == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="pegawai" {{ $user['roles'] == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
    </select>
</div>
<div>
    <button class="btn btn-primary" type="submit">Update User</button>
</div>
</form>

</div>
@endsection
