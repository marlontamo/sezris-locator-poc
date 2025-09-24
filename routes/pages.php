<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;

Route::resource('pages',PagesController::class);