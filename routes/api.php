<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketResponseController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', [TicketController::class, 'index']);
Route::post('/ticket', [TicketController::class, 'store']);
Route::get('/ticket/{ticket_id}', [TicketController::class, 'show']);
Route::put('/ticket/{ticket_id}', [TicketController::class, 'update']);
Route::delete('/ticket/{ticket_id}', [TicketController::class, 'destroy']);
Route::put('/ticket/{ticket_id}/reopen', [TicketController::class, 'reopen']);
Route::post('/tickets/{ticket_id}/responses', [TicketResponseController::class, 'store']);


/*Route::get('/', [TicketController::class, 'index']);
Route::post('/ticket', [TicketController::class, 'store']);
Route::get('{ticket}', [TicketController::class, 'show']);
Route::put('{ticket}', [TicketController::class, 'update']);
Route::delete('{ticket}', [TicketController::class, 'destroy']);
Route::put('{ticket}/reopen', [TicketController::class, 'reopen']);
Route::post('tickets/{ticket}/responses', [TicketResponseController::class, 'store']);*/
// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware('auth:api')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout']);
    // User Profile Route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Customer Routes
    Route::middleware(['role:Customer'])->group(function () {
     //   Route::resource('tickets', TicketController::class)->except(['index']);
       /* Route::get('/', [TicketController::class, 'index']);
        Route::post('/', [TicketController::class, 'store']);
        Route::get('{ticket}', [TicketController::class, 'show']);
        Route::put('{ticket}', [TicketController::class, 'update']);
        Route::delete('{ticket}', [TicketController::class, 'destroy']);
        Route::put('{ticket}/reopen', [TicketController::class, 'reopen']);
        Route::post('tickets/{ticket}/responses', [TicketResponseController::class, 'store']);*/
    });

    // Support Agent Routes
    Route::middleware(['role:Support Agent'])->group(function () {
        Route::get('tickets', [TicketController::class, 'index']);
        Route::put('tickets/{ticket}/close', [TicketController::class, 'close']);
    });

    // Admin Routes
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('admin/statistics', [AdminController::class, 'statistics']);
    });
    
    // General Ticket Management
    /*Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);
        Route::post('/ticket', [TicketController::class, 'store']);
        Route::get('{ticket}', [TicketController::class, 'show']);
        Route::put('{ticket}', [TicketController::class, 'update']);
        Route::delete('{ticket}', [TicketController::class, 'destroy']);
        Route::put('{ticket}/reopen', [TicketController::class, 'reopen']);
    });*/
    
    // Ticket Responses
    Route::apiResource('tickets.responses', TicketResponseController::class);
});

// Article Routes (no auth restriction)
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::post('/', [ArticleController::class, 'store']);
    Route::get('/{article}', [ArticleController::class, 'show']);
    Route::put('/{article}', [ArticleController::class, 'update']);
    Route::delete('/{article}', [ArticleController::class, 'destroy']);
});
