<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\RequirementController;
use App\Models\User;
use App\Helpers\PermitHelper;
use Illuminate\Support\Facades\Storage;

Route::middleware(['auth'])->group(function () {
    Route::resource('requirements', RequirementController::class)
        ->only(['show', 'store']);
});

