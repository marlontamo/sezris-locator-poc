<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UploadController;
use App\Models\User;
use App\Helpers\PermitHelper;

Route::middleware(['auth'])->group(function () {
    Route::get('/uploads', [UploadController::class, 'index'])->name('uploads.index');
    Route::get('/uploads/create', [UploadController::class, 'create'])->name('uploads.create');
    Route::post('/uploads', [UploadController::class, 'store'])->name('uploads.store');
    Route::get('/uploads/{id}/download', [UploadController::class, 'download'])->name('uploads.download');
    Route::delete('/uploads/{id}', [UploadController::class, 'destroy'])->name('uploads.destroy');
    Route::get('/uploads/{id}/view', [UploadController::class, 'view'])
        ->name('uploads.view')
        ->middleware('signed'); // must be signed + logged in
});

Route::get('/usercheck-uploaded',function(){
    $user = auth()->user()->uploads()->get();
    return dd(count($user));
});
Route::get('/test-validity/{days}', function ($days) {
    $expiry = PermitHelper::computeValidity((int) $days);

    return "<h1>Expiry Date after {$days} working days:<br>" .
           "Permit will expire on: " . $expiry->toFormattedDateString() . "<br>" .
           "That’s " . $expiry->diffForHumans()."</h1>";
});