<?php

use Illuminate\Support\Facades\Route;
use Indianic\Textareafield\Http\Controllers\ChatGptController;

Route::post('chat', [ChatGptController::class, 'chat']);
Route::get('language', [ChatGptController::class, 'language']);
