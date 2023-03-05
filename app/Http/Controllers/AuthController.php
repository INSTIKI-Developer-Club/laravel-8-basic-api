<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /*
    | regiter user
    | --------------------
    | param : $request 
    */

    public function registerUser(Request $request) {
        try {
            $validated = $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'alamat' => 'required|string'
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $user = User::create($validated);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Success register User',
            ], 201);

        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation Exception',
                'error' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed register User',
                'error' => $e->getMessage()
            ], 500);

        }
    }
}
