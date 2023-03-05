<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function registerUser (Request $request) {
        try {
            
            $validate = $this->validate($request,[
                'name'=>'required|string',
                'email'=>'required|email|unique:users',
                'password'=>'required|string|min:6',
                'alamat'=>'required|string'
            ]);

            // enkripsi password
            $validate["password"] = bcrypt($validate["password"]);


             
            $user = User::create($validate);


            // generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // send response
            return response()->json([
                'message'=>'user register succesfully',
                'user'=>$user,
                'access_token'=>$token
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message'=>'Validation Exception',
                'error'=>$e->errors()
            ],422);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Failed to register now',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
