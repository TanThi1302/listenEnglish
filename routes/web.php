<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\CreateController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\LessonManagementController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\comunicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Lessons
Route::get('/lessons/{slug}', [LessonController::class, 'show'])->name('lessons.show');

// Leaderboard
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Auth Routes (from Breeze)
require __DIR__.'/auth.php';

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Learning
    Route::get('/lessons/{slug}/learn', [LearningController::class, 'learn'])->name('lessons.learn');
    Route::post('/learning/check-answer', [LearningController::class, 'checkAnswer'])->name('learning.check');
    Route::get('/learning/hint/{partId}', [LearningController::class, 'getHint'])->name('learning.hint');
    
    // Bookmarks
    Route::post('/lessons/{id}/bookmark', [LessonController::class, 'bookmark'])->name('lessons.bookmark');
    
    // Comments
    Route::post('/lessons/{id}/comment', [LessonController::class, 'comment'])->name('lessons.comment');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

/// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // 1. Create New Lesson
    Route::get('/lessons/create', [CreateController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [CreateController::class, 'store'])->name('lessons.store');
    
    // 2. Manage Lessons
    Route::get('/lessons', [LessonManagementController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{id}/edit', [LessonManagementController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{id}', [LessonManagementController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{id}', [LessonManagementController::class, 'destroy'])->name('lessons.destroy');
    
    // Lesson Parts
    Route::post('lessons/{id}/parts', [LessonManagementController::class, 'addPart'])->name('lessons.parts.add');
    Route::delete('lessons/parts/{id}', [LessonManagementController::class, 'deletePart'])->name('lessons.parts.delete');
    
    // 3. Manage Categories
    Route::get('/categories', [CategoryManagementController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryManagementController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryManagementController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryManagementController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryManagementController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryManagementController::class, 'destroy'])->name('categories.destroy');
    
    // 4. Manage Users
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');
});
// Test route - TEMPORARY
Route::get('/test-db', function() {
    try {
        $categoriesCount = \App\Models\Category::count();
        $lessonsCount = \App\Models\Lesson::count();
        $usersCount = \App\Models\User::count();
        
        $categories = \App\Models\Category::where('is_active', 1)->get();
        
        return response()->json([
            'status' => 'OK',
            'counts' => [
                'categories' => $categoriesCount,
                'lessons' => $lessonsCount,
                'users' => $usersCount,
            ],
            'active_categories' => $categories->count(),
            'categories_list' => $categories->pluck('name'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
});
Route::get('/comunication', [ComunicationController::class, 'showAssistant']);

// Route API để proxy yêu cầu đến Node.js AI server
Route::post('/api/chat-with-ai', [ComunicationController::class, 'chat']);


### 2️⃣ Truy cập URL test

http://localhost:8000/test-db
