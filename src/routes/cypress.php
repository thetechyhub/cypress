<?php

use Illuminate\Support\Facades\Route;
use Laracasts\Cypress\Controllers\CypressController;

Route::post('/cypress/factory', [CypressController::class, 'factory'])->name('cypress.factory');
Route::post('/cypress/login', [CypressController::class, 'login'])->name('cypress.login');
Route::post('/cypress/logout', [CypressController::class, 'logout'])->name('cypress.logout');
Route::post('/cypress/artisan', [CypressController::class, 'artisan'])->name('cypress.artisan');
Route::post('/cypress/run-php', [CypressController::class, 'runPhp'])->name('cypress.run-php');
Route::get('/cypress/csrf_token', [CypressController::class, 'csrfToken'])->name('cypress.csrf-token');
