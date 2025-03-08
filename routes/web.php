<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;

Route::get('/', fn() => view('welcome'));

Route::get('/login/{id}', fn($id)=> Auth::loginUsingId($id));
