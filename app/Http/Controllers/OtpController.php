<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'type' => 'required|in:email,phone',
        ]);

        $user = User::where('email', $request->identifier)
                    ->orWhere('phone', $request->identifier)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $code = rand(100000, 999999);
        $otp = Otp::create([
            'identifier' => $request->identifier,
            'type' => $request->type,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($request->type === 'email') {
            \Illuminate\Support\Facades\Mail::raw("Your OTP is: $code", function ($message) use ($request) {
                $message->to($request->identifier)->subject('Your OTP Code');
            });
        } else {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $twilio->messages->create($request->identifier, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => "Your OTP is: $code",
            ]);
        }

        return response()->json(['message' => 'OTP sent']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'type' => 'required|in:email,phone',
            'code' => 'required|string|size:6',
        ]);

        $otp = Otp::where('identifier', $request->identifier)
                  ->where('type', $request->type)
                  ->where('code', $request->code)
                  ->where('expires_at', '>', now())
                  ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $otp->delete();
        return response()->json(['message' => 'OTP verified']);
    }
}