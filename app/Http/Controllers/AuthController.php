<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function checkUserLogin(Request $request){
        try{
            $request->validate([
                "email" => "required",
                "password" => "required",
            ]);

            $userCredentials = $request->only('email', 'password');
            if(!$token = JWTAuth::attempt($userCredentials)){
                return response()->json([
                    "message" => "Email or password incorrect!",
                ],404);
            }

            return response()->json([
                "userData" => JWTAuth::user(),
                "token" => $token,
            ]);

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ],500);
        }
    }


    public function sendVerificationCode(Request $request){
        try{

            $request->validate([
                "email" => "required|email",
            ]);

            $email = $request->email;
            $userExists = User::where("email",$email)->first();

            if($userExists){
                return response()->json([
                    "message" => "User assoiciated with this email already exists!",
                ],401);
            }

            $verificationCode = rand(100000,999999);

            DB::table('verification_codes')->insert([
                'email' => $email,
                'code' => $verificationCode,
                'expires_at' => now()->addMinutes(2),
            ]);

            Mail::send('email.verification_code', ['verificationCode' => $verificationCode], function($message) use ($email) {
                $message->to($email);
                $message->subject('Verification Code');
            });

            return response()->json([
                "message" => "Verification code sent successfully!",
            ]);

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ],500);
        }
    }

    public function verifyCode(Request $request){
        try{

            $request->validate([
                "email" => "required|email",
                "vCode" => "required",
                "fullName" => "required",
                "password" => "required",
            ]);

            $fullName = $request->fullName;
            $vCode = $request->vCode;
            $email = $request->email;
            $password = $request->password;

            $verificationCode = DB::table("verification_codes")
                                ->where("email",$email)
                                ->where("code",$vCode)
                                ->where("expires_at",">",now())
                                ->first();

            if(!$verificationCode){
                return response()->json([
                    "message" => "Verification code expired or incorrect!",
                ],404);
            }

            User::create([
                "name" => $fullName,
                "email" => $email,
                "password" => Hash::make($password),
            ]);

            return response()->json([
                "message" => "Successfully registred"
            ]);

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ]);
        }
    }


    public function sendResetPasswordLink(Request $request){
        try{

            $request->validate([
                "email" => "required|email",
            ]);

            $userExists = User::where("email",$request->email)
                                ->first();

            if(!$userExists){
                return response()->json([
                    "message" => "User with this email does not exist",
                ]);
            }

            $status = Password::sendResetLink($request->only('email'));

            if($status === Password::RESET_LINK_SENT){
                return response()->json([
                    "message" => "Your reset link has been sent to your email",
                ]);
            }

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ]);
        }
    }


    public function resetPassword(Request $request){
        try{

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            if($status === Password::PASSWORD_RESET){
                return response()->json([
                    "message" => "Password reseted successfully!",
                ]);
            }
            return response()->json([
                "message" => __($status)
            ],401);

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ]);
        }
    }

    public function logout(){
        try{

            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                "message" => "User logged out successfully!",
            ]);

        }catch(Exception $ex){
            return response()->json([
                "message" => $ex->getMessage(),
            ]);
        }
    }
}
