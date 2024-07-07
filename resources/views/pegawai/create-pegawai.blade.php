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
<form action="" method="POST">
@csrf
<div class="my-3">
    <label for="email">Email:</label>
    <input  class="form-control" value="yudha@gmail.com" type="email" id="email" name="email" required>
</div>
<div class="my-3">
    <label for="password">Password:</label>
    <input class="form-control" value="12345678" type="password" id="password" name="password" required>
</div>
<div class="my-3">
    <label for="name">Name:</label>
    <input class="form-control" value="yudha" type="text" id="name" name="name" required>
</div>
<div class="my-3">
    <label for="roles">Roles:</label>
    <select id="roles" name="roles" class="custom-select">
        <option selected disabled>Open this select menu</option>
        <option value="admin">Admin</option>
        <option  value="pegawai">Pegawai</option>
      </select>

</div>
<div>
    <button  class="btn-primary btn" type="submit">Create User</button>
</div>
</form>
</div>
@endsection
