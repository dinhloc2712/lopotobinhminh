<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'province' => ['required'],
            'district' => ['required'],
            'ward' => ['required'],
            'street_address' => ['required', 'string'],
        ]);

        // Note: 'district' is not currently a column in 'users' table.
        // We concatenate it into 'street_address' if needed, or just store the detail.
        // For now, let's combine selection names if we had them, 
        // but Since we only have codes, we'll store codes and the detail address.
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role_id' => 5, // Customer Role
            'province_id' => $request->province,
            'ward_id' => $request->ward,
            'street_address' => $request->street_address,
            'is_active' => 1,
        ]);

        Auth::login($user);

        $redirectTo = $request->input('redirect_to', route('admin.profile.show'));
        
        return redirect($redirectTo)->with('success', 'Đăng ký tài khoản thành công!');
    }
}
