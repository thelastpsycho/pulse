<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DmLogBookController;
use App\Http\Controllers\Api\IssueCategoryController;
use App\Http\Controllers\Api\IssueCommentController;
use App\Http\Controllers\Api\IssueController;
use App\Http\Controllers\Api\IssueTypeController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SavedFilterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (if needed for authentication)
// Route::post('/login', [AuthController::class, 'login']);

// Protected API Routes
Route::middleware(['auth'])->group(function () {
    // Reports API (returns JSON for charts)
    Route::get('/reports/month', [ReportController::class, 'month'])->name('api.reports.month');
    Route::get('/reports/year', [ReportController::class, 'year'])->name('api.reports.year');

    // Issues API
    Route::name('api.')->group(function () {
        Route::apiResource('issues', IssueController::class);
        Route::post('/issues/{issue}/close', [IssueController::class, 'close'])->name('issues.close');
        Route::post('/issues/{issue}/reopen', [IssueController::class, 'reopen'])->name('issues.reopen');
        Route::get('/issues/{issue}/comments', [IssueCommentController::class, 'index'])->name('issues.comments');
    });

    // Issue Comments API
    Route::apiResource('issue-comments', IssueCommentController::class);

    // Departments API
    Route::apiResource('departments', DepartmentController::class);

    // Issue Types API
    Route::apiResource('issue-types', IssueTypeController::class);

    // Issue Categories API
    Route::apiResource('issue-categories', IssueCategoryController::class);

    // Users API
    Route::apiResource('users', UserController::class);

    // Roles API
    Route::apiResource('roles', RoleController::class);

    // Permissions API (read-only)
    Route::apiResource('permissions', PermissionController::class)->only(['index', 'show']);

    // Activity Logs API (read-only)
    Route::apiResource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

    // Saved Filters API
    Route::apiResource('saved-filters', SavedFilterController::class);

    // DM Log Book API
    Route::apiResource('logbook', DmLogBookController::class);
});
