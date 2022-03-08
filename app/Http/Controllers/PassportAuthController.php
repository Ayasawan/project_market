<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class PassportAuthController extends Controller
{
    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if($validator->fails()){
            return response()->json( $validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            throw new AuthenticationException();
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $data["user"] = $user;
        $data["token_type"] = 'Bearer';
        $data["access_token"] = $tokenResult->accessToken;
        return response()->json($data,Response::HTTP_OK);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255' ,'unique:users',],
            'password' => ['required', 'string', 'min:8'],
          'mobile' => [ 'required' , 'string','min:10'],
        ]);
        if($validator->fails()){
            return $validator->errors()->all();
        }

        $request['password'] = Hash::make($request['password']);

        $user = User::create([
            'name'=> $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'mobile' => $request->mobile,
        ]);
        $tokenResult = $user->createToken('Personal Access Token');
        $data["message"] = 'User Successfully registered';
        $data["user"] = $user;
        $data["token_type"] = 'Bearer';
        $data["access_token"] = $tokenResult->accessToken;

        return response()->json($data,Response::HTTP_OK);
    }
    public function logout(Request $request)
    {
     $token=$request->user()->token();
     $token->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
