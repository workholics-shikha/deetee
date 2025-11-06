<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{User,OperatorAttendance};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};

class OperatorAuthController extends Controller
{

    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:operators,email',
            'password' => 'required|string|min:8|confirmed', // Ensure password confirmation
        ]);

        // Create a new operator
        $operator = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a token for the newly registered operator
        $token = $operator->createToken('operator-token')->plainTextToken;

        // Return the operator details and token
        return response()->json([
            'message'   => 'Operator registered successfully',
            'operator'  => [
                'id'    => $operator->id,
                'name'  => $operator->name,
                'email' => $operator->email,
            ],
            'token' => $token,
        ], 201);
    }
  
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required',
        ]);
        
         // Check if validation fails
        if ($validation->fails()) {
            return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
        }
  
        $operator = User::select('id', 'role','name', 'email', 'profile_image', 'unit', 'unit_name', 'username')
        ->where(function ($query) use ($request) {
            $query->where('id', $request->username)
                  ->orWhere('username', $request->username);
        })
        ->first();

        if (!$operator) {
            return response()->json(['message' => 'Invalid credentials or Invalid QR'], 401);
        }

        if ($operator->role!='2') {
            return response()->json(['message' => 'Access denied for role'], 401);
        }
 
       
            // No open record -> insert new start time
            OperatorAttendance::create([
                'operator_id' => $operator->id,
                'start_date_time' => now(),
            ]);
      

        $tokenResult = $operator->createToken('operator-token');
        $token       = $tokenResult->plainTextToken;
 
        $tokenResult->accessToken->expires_at = now()->addDays(7); // expiry to 7 days
        $tokenResult->accessToken->save();
           
         return response()->json([
            'message'   => 'Operator login successfully',
            'data' => [
                'id'    => $operator->id,
                'name'  => $operator->name,
                'email' => $operator->email,
                'email' => $operator->email,
                'username' => $operator->username,
                'unit'  => $operator->unit_name,
                'unit_no' => 'Unit-'.$operator->unit,
                'profile_image' => $operator->profile_image,
            ],
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $attendence = OperatorAttendance::where('operator_id', $request->user()->id)
            ->latest()
            ->first();

        if ($attendence) {
            $attendence->end_date_time = now();
            $attendence->save();
        }

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
 
}
