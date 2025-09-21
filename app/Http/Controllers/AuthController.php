<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ServiceEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|in:admin,employee',
            ]);
            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            return successResponse('User saved successfully.', ['role' => $user->role]);
        } catch (ValidationException $e) {
            return errorResponse($e->getMessage(), $e->errors());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required',
                'password' => 'required',
            ]);
            $user = User::where('mobile', $request->mobile)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['status' => false, 'message' => 'Authentication failed']);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return successResponse(
                'Logged-in successfully!',
                [
                    'access_token' => $token,
                    'user' => $user,
                    'role' => $user->role,
                    'token_type' => 'Bearer'
                ]
            );
        } catch (ValidationException $e) {
            return errorResponse($e->getMessage(), $e->errors());
        }
    }

    public function dashboard()
    {
        try {
            $customerCount = Customer::all()->count();
            $pendingServiceCount = ServiceEntry::where('status', 'pending')->with(['customer', 'service'])->get()->count();
            $allServiceCount = ServiceEntry::with(['customer', 'service'])->get()->count();
            return successResponse(
                'Dashboard loaded successfully.',
                [
                    'customer_count' => $customerCount,
                    'pending_service_count' => $pendingServiceCount,
                    'all_service_count' => $allServiceCount,
                ]
            );
        } catch (ValidationException $e) {
            return errorResponse($e->getMessage(), $e->errors());
        }
    }
}
