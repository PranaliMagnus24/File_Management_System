<?php

use App\Http\Controllers\Backend\MasterSettings\RolePermission\PermissionManagementController;
use App\Http\Controllers\Backend\MasterSettings\RolePermission\RoleManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\AdminDashboardController;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Role & Permission Management
    Route::prefix('/admin')->name('admin.')->group(function() {
        // Permission Routes
        Route::get('permissions', [PermissionManagementController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [PermissionManagementController::class, 'store'])->name('permissions.store');
        Route::get('permissions/edit', [PermissionManagementController::class, 'edit'])->name('permissions.edit');
        Route::post('permissions/update', [PermissionManagementController::class, 'update'])->name('permissions.update');
        Route::post('permissions/delete', [PermissionManagementController::class, 'destroy'])->name('permissions.destroy');
        // Role Routes
        Route::get('roles', [RoleManagementController::class, 'index'])->name('roles.index');
        Route::post('roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::get('roles/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
        Route::delete('roles/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
        Route::get('roles/{id}/permissions', [RoleManagementController::class, 'permissionToRole'])->name('roles.permissions');
        Route::patch('roles/{id}/permissions', [RoleManagementController::class, 'updatePermissionToRole'])->name('roles.updatePermissions');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

