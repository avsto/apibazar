<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function register(Request $request)
    {
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['password'] = Hash::make('password');
        User::create($data);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'signature' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'fcm_token' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'message' => $validator->errors()->get('phone')[0]]);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user) {

            /**
             * @update location
             */
            User::where('phone', $request->phone)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'fcm_token' => $request->fcm_token,
            ]);


            $otp = rand(1111, 9999);
            $message = "$otp is your OTP to proceed on QuickFinz. Do not share your OTP with anyone. It is valid for the next 5 minutes.";
            if ($request->has('signature')) {
                $message = "$otp is your OTP to proceed on QuickFinz. Do not share your OTP with anyone. It is valid for the next 5 minutes {$request->signature}.";
            }
            sendOtp($request->phone, $message);

            session([
                'userid' => $user->id,
                'verify_token' => md5($otp . '_' . $user->id),
                'otp' => $otp,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Opt has been sent on register mobile number',
                'data' => ['verify_token' => md5($otp . '_' . $user->id)]
            ]);

        }

        return response()->json(['status' => 201, 'message' => 'Invalid Phone Number']);

    }

    public function verifyotp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verify_token' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'message' => $validator->errors()]);
        }

        $verify_token = session()->get('verify_token');
        $otp = session()->get('otp');
        $userid = session()->get('userid');

        if ($request->verify_token != $verify_token) {
            return response()->json(['status' => 201, 'message' => 'Invalid Verify token']);
        }

        if ($otp != $request->otp) {
            return response()->json(['status' => 201, 'message' => 'wrong OTP']);
        }

        Auth::loginUsingId($userid);
        session()->forget(['userid', 'verify_token', 'otp']);

        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'message' => 'User login successfully',
            'data' => $user
        ]);
    }


    public function profile()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'fetch data successfully',
                'data' => $user
            ]);
        }
        return response()->json([
            'status' => 201,
            'message' => 'user not login',
            'data' => []
        ]);
    }


    public function logout() {
        Auth::logout();
    }

}
