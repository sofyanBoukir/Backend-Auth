<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix("auth")->group(function(){
    Route::post("/login", [AuthController::class,'checkUserLogin']); // check user login email,password
    Route::post("/sendVerificationCode", [AuthController::class,'sendVerificationCode']); // send a verification code to email who registered
    Route::post("/verifyCode", [AuthController::class,'verifyCode']);   // verify the code sent to email
    Route::post("/sendResetLink", [AuthController::class,"sendResetPasswordLink"]); // send a reset password link to email provided
    Route::post("/resetPassword", [AuthController::class,"resetPassword"]);  // reset the password
    Route::post("/logout", [AuthController::class,"logout"]); // logout the user
});
