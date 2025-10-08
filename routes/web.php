<?php

use App\Http\Controllers\Backend\Department\DepartmentController;
use App\Http\Controllers\Backend\MasterSettings\GeneralSetting\GeneralSettingController;
use App\Http\Controllers\Backend\MasterSettings\RolePermission\PermissionManagementController;
use App\Http\Controllers\Backend\MasterSettings\RolePermission\RoleManagementController;
use App\Http\Controllers\Backend\MasterSettings\RolePermission\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\AdminDashboardController;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware(['auth', 'verified','isAdmin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Role & Permission Management
    Route::prefix('/admin')->name('admin.')->group(function() {
        // Permission Routes
       Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('permissions.index')->middleware('permission:permission-view');
       Route::post('/permissions', [PermissionManagementController::class, 'store'])->name('permissions.store');
       Route::get('/permissions/{id}/edit', [PermissionManagementController::class, 'edit'])->name('permissions.edit')->middleware('permission:permission-edit');
       Route::put('/permissions/{id}', [PermissionManagementController::class, 'update'])->name('permissions.update')->middleware('permission:permission-update');
       Route::delete('/permissions/{id}', [PermissionManagementController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:permission-delete');
        // Role Routes
        Route::get('roles', [RoleManagementController::class, 'index'])->name('roles.index')->middleware('permission:role-view');
        Route::post('roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::get('roles/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit')->middleware('permission:role-edit');
        Route::put('roles/{id}', [RoleManagementController::class, 'update'])->name('roles.update')->middleware('permission:role-update');
        Route::delete('roles/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy')->middleware('permission:role-delete');
        Route::get('roles/{id}/permissions', [RoleManagementController::class, 'permissionToRole'])->name('roles.permissions');
        Route::patch('roles/{id}/permissions', [RoleManagementController::class, 'updatePermissionToRole'])->name('roles.updatePermissions');
        // User Management Routes
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index')->middleware('permission:user-view');
        Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit')->middleware('permission:user-edit');
        Route::put('users/{id}', [UserManagementController::class, 'update'])->name('users.update')->middleware('permission:user-update');
        Route::delete('users/{id}', [UserManagementController::class, 'destroy'])->name('users.delete')->middleware('permission:user-delete');

        //General Setting
        Route::get('general/settings',[GeneralSettingController::class,'index'])->name('create.generalSetting');
        Route::post('general/settings',[GeneralSettingController::class,'store'])->name('store.generalSetting');

        //Department Management
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

