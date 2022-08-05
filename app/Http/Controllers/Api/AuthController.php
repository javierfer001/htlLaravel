<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
            'confirmed' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->resError(
                $validator->errors()
            );
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if($user->save()){
            // Create the token (optionally we can add scopes here)
            $success['token'] =  $user->createToken('accessToken')->accessToken;
            $success['name'] =  $user->name;
            $success['email'] = $user->email;
            return $this->resSuccess($success, 'User register successfully.',);
        }else{
            return $this->resError('Provide proper details');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if($validator->fails()){
            return $this->resError(
                $validator->errors()
            );
        }


        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $tokenResult = $user->createToken('accessToken');
                $token = $tokenResult->plainTextToken;
                return $this->resSuccess([
                    'token' => $token,
                    'user'  => $user->toArray(),
                ], "Welcome {$user->name}");
            } else {
                return $this->resError('Password mismatch');
            }
        } else {
            return $this->resError('User does not exist');
        }
    }
}
