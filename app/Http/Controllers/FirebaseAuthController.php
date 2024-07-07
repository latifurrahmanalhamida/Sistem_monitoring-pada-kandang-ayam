<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuthController extends Controller
{
    protected  $auth;
    public function __construct()
    {
        $this->auth = Firebase::auth();
    }
}
