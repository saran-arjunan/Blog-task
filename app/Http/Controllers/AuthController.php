<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Exception;

class AuthController extends Controller
{
    public function Check_Name_Available(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'user_name' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $user_name = $request->input('user_name');

            $check_name = DB::table('users')->where('name', $user_name)->count();

            if ($check_name >= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Name already exists'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Name available'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function CreateUser(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:8',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|numeric|digits:10|unique:users,mobile_number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user_name = $request->input('user_name');
        $password = $request->input('password');
        $email = $request->input('email');
        $mobile_number = $request->input('mobile_number');

        $check_user = DB::table('users')
            ->where('mobile_number', $mobile_number)
            ->orWhere('email', $email)
            ->count();

        if ($check_user >= 1) {
            return response()->json([
                'status' => false,
                'message' => 'User already exists'
            ]);
        } else {
            $insert = DB::table('users')->insertGetId([
                'name' => $user_name,
                'password' => md5($password),
                'email' => $email,
                'mobile_number' => $mobile_number
            ]);

            if ($insert) {
                return response()->json([
                    'status' => true,
                    'message' => 'Registration completed'
                ]);
            }
        }
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ]);
    }
}
public function ValidateUser(Request $request){
    try{
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user_name = $request->input('user_name');
        $password = md5($request->input('password'));

        $user = User::where('name', $user_name)
                ->where('password', $password)
                ->first();

        if ($user) {
            Auth::login($user);
            $user_id = Auth::user()->id;

            return response()->json([
                'status' => true,
                'message'=>'Login Successfully',
                'user_id' => $user_id
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    } catch(Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ]);
    }
}
public function Login(Request $request){
    return view('Auth.Login');
}

public function Logout(Request $request)
{
    Auth::logout();

    return redirect('Login');
}
public function RegisterForm(){
    return View('Auth.Register');
}


}
