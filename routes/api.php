<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// All routes here start with /api

Route::get('/profiles', [ProfileController::class, 'index']);         // list all

Route::get('/profiles/{id}', [ProfileController::class, 'show']);     // get one



Route::post('/profiles', [ProfileController::class, 'store']);        // create


// ONE BUG here fix it... 
Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']); // delete

//MISSING update route here add it!
//HERE I A M MUHIE.ALDEEN I WILL ADD THE UPDATE
Route::put('/profiles/{id}', [ProfileController::class, 'update']); // update