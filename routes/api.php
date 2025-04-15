<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VacancyController;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Публичные маршруты
Route::post('/login', [AuthController::class, 'login']);
Route::get('/vacancies', [VacancyController::class, 'publicIndex']);
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'getVacancy']);
Route::post('vacancies/respond', [VacancyController::class, 'respond']);

Route::get('/test-telegram', function () {
    $botToken =  env('TELEGRAM_BOT_TOKEN');
    $message = 'Тестовое сообщение от Laravel';

    $response = Http::get("https://api.telegram.org/bot{$botToken}/getUpdates");

    return $response->json();
});
// Защищённые маршруты (только для админов)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/vacancies', VacancyController::class)->except(['index', 'show']);
});
