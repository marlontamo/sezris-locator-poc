<?php
use App\Http\Controllers\PermitController;
use App\Http\Controllers\ApprovalController;


Route::resource('permits', PermitController::class);
Route::get('/permits',[PermitController::class, 'index'])->name('permits.index');
Route::get('/permits/{id}', [PermitController::class, 'show'])->name('permits.show');
Route::get('/permits/{id}', [PermitController::class, 'show'])->name('permits.show');
Route::post('/approvals/{id}', [PermitController::class, 'updateApproval'])->name('approvals.update');
Route::post('/approvals/{approval}/approve', [PermitController::class, 'approve'])->name('approvals.approve');
Route::post('/approvals/{approval}/reject', [PermitController::class, 'reject'])->name('approvals.reject');
Route::post('/approvals/{approval}/return', [ApprovalController::class, 'returnToPrevious'])->name('approvals.return');



Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
Route::post('/approvals/{approval}/return', [ApprovalController::class, 'returnToPrevious'])->name('approvals.return');