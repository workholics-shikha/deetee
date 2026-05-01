<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminForgotRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $roleIds = [1, 3, 4, 5, 6, 7]; // -Allowed role IDs

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (in_array($user->role, $roleIds)) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome to the Admin Dashboard!');
            }

            // Unauthorized role: log them out immediately
            Auth::logout();
        }

        return back()->withErrors(['login' => 'Invalid credentials or not authorized.'])->withInput();
    }

    public function login1(AdminLoginRequest $request)
    {
        // Validate the incoming request
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validation->fails()) {
            return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
        }

        // Attempt to find the admin user, bypassing the global scope
        $user = User::withoutGlobalScope('active')
            ->where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        // Check if user exists and manually verify the password
        if ($user && Hash::check($request->password, $user->password)) {
            // Log the user in
            Auth::login($user);

            // Generate token
            $token = $user->createToken('device_token')->plainTextToken;

            // Return success response with token and user information
            return response()->json([
                'status' => true,
                'message' => 'Login success',
                'data' => [
                    'token' => $token,
                    'user_id' => $user->id,
                ],
            ]);
        }

        // Return error response if credentials are invalid
        return response()->json(['status' => false, 'message' => 'Invalid credentials', 'data' => null]);
    }

    public function showForgotPassForm()
    {
        return view('auth.forgot');
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function forgot(AdminForgotRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'role' => 'admin'])) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome to the Admin Dashboard!');
        }

        return back()->withErrors(['login' => 'Email Id does not match with records.'])->withInput();
    }

    /**
     * Helper function to send reset email.
     */
    private function sendResetEmail($email, $resetCode, $resetUrl)
    {
        $subject = 'Password Reset Request';

        $data = [
            'resetCode' => $resetCode,
            'resetUrl' => $resetUrl,
        ];

        Mail::send('emails.forgot_password', $data, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    public function thankyou(Request $request)
    {

        $checkToken = PasswordReset::where('token', $request->token)->first();

        if (! $checkToken) {
            return back()->withErrors(['login' => 'Unauthenticated.']);
        }

        return view('auth/thankyou');
    }

    public function forgotPassword(AdminForgotRequest $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate reset code and URL
        $resetCode = Str::random(6);
        $code = Crypt::encrypt($resetCode);
        $resetUrl = url('/reset-password'.'/'.$code);
        $token = Hash::make($resetCode);

        // Save the reset code (hashed) in the database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'code' => $resetCode, 'created_at' => now()]
        );

        // Send reset email
        $this->sendResetEmail($request->email, $resetCode, $resetUrl);

        return redirect('thankyou?token='.$token)->with('success', 'Reset email sent successfully!');
    }

    public function resetPassForm($code)
    {

        try {
            // Decrypt the code
            $decryptedCode = Crypt::decrypt($code);

            // Check if the token exists in the database
            $checkToken = PasswordReset::where('code', $decryptedCode)->first();

            if (! $checkToken) {
                // Redirect to login if the token is invalid
                return redirect()->route('login')->with('error', 'Invalid or expired reset token.');
            }

            // If token is valid, show the reset password view
            return view('auth.reset', ['code' => $code]);
        } catch (DecryptException $e) {
            // Handle decryption failure
            return redirect()->route('login')->with('error', 'Invalid reset token.');
        }
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        // Retrieve the reset record
        $resetRecord = DB::table('password_resets')->where('email', $request->email)->first();

        if (! $resetRecord || ! Hash::check($request->code, $resetRecord->token)) {
            return response()->json(['message' => 'Invalid reset code or email.'], 400);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('login')->with('success', 'Password reset successfully. Try to login with new password!');
    }

    public function checkEmail(Request $request)
    {

        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if ($passwordReset) {
            // Check if the created_at is less than 2 minutes from now
            $isWithinTwoMinutes = Carbon::parse($passwordReset->created_at)->gt(Carbon::now()->subMinutes(2));

            if ($isWithinTwoMinutes) {
                // Disable the button
                return response()->json(['button_disabled' => true, 'message' => 'Please wait 2 minutes before requesting another reset email.']);
            } else {
                // Enable the button
                return response()->json(['button_disabled' => false, 'message' => 'You can request a reset email now.']);
            }
        } else {
            // Handle case where no record is found
            return response()->json(['error' => 'No reset request found for this email.']);
        }
    }

    public function resendRequest(Request $request)
    {

        $checkToken = PasswordReset::where('token', $request->token)->first();

        if (! $checkToken) {
            return back()->withErrors(['login' => 'Unauthenticated.']);
        }

        // Generate reset code and URL
        $email = $checkToken->email;
        $resetCode = Str::random(6);
        $code = Crypt::encrypt($resetCode);
        $resetUrl = url('/reset-password'.'/'.$code);
        $token = Hash::make($resetCode);

        // Save the reset code (hashed) in the database
        $sendMail = DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'code' => $resetCode, 'created_at' => now()]
        );

        // Send reset email
        $this->sendResetEmail($email, $resetCode, $resetUrl);

        return response()->json(['button_disabled' => true, 'message' => 'Resend email sent successfully!.']);
    }
}
