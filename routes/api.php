<?php

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // API for learning
    Route::post('/lessons/check-answer', [LearningController::class, 'checkAnswer']);
    Route::get('/lessons/{id}/parts', function ($id) {
        $lesson = \App\Models\Lesson::with('parts')->findOrFail($id);
        return response()->json($lesson->parts);
    });
});
