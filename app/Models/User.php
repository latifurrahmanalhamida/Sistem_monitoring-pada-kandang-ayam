<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        // tambahkan kolom lain yang ingin Anda masukkan di sini
    ];

    protected $hidden = [
        'password',
        'remember_token',
        // tambahkan kolom lain yang ingin disembunyikan di sini
    ];
}
