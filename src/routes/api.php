<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/v1/check-me', function (Request $request) {
    $auth = $request->user();
    $user = $auth->user;

    return [
        'id' => $user->id->asString(),
        'name' => $user->name,
        'email' => $user->email->asString(),
        'created_at' => $user->createdAt->format(
            format: 'Y-m-d H:i:s'
        ),
        'updated_at' => $user->updatedAt?->format(
            format: 'Y-m-d H:i:s'
        ),
    ];
})->middleware('auth:api');
