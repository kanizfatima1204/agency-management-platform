<?php use Illuminate\Support\Facades\Route; Route::middleware('auth')->get('/me',fn($r)=>$r->user());
