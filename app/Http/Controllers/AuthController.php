<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:End User,Proposer,Recycling Agency,Awareness Organization',
            'organization_name' => 'required_if:role,Recycling Agency,Awareness Organization',
            'contact_number' => 'required_unless:role,End User|nullable|string|max:20',
            'registration_certificate' => 'required_unless:role,End User|nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $certificatePath = null;
        if ($request->hasFile('registration_certificate')) {
            $certificatePath = $request->file('registration_certificate')->store('registration_certificates', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'organization_name' => $request->organization_name,
            'contact_number' => $request->contact_number,
            'registration_certificate' => $certificatePath,
            'profile_photo' => $photoPath,
            'is_validated' => $request->role === 'End User',
            'status' => $request->role === 'End User' ? 'active' : 'pending',
        ]);

        if ($user->status === 'active') {
            Auth::login($user);
            return $this->redirectByRole($user->role);
        }

        return redirect()->route('login')->with('status', 'Registration successful. Please wait for admin approval.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->status === 'pending') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is pending admin approval.',
                ]);
            }
            
            if ($user->status === 'rejected') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account registration has been rejected.',
                ]);
            }

            $request->session()->regenerate();
            return $this->redirectByRole($user->role);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectByRole($role)
    {
        return match ($role) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Proposer', 'Recycling Agency', 'Awareness Organization' => redirect()->route('proposer.dashboard'),
            default => redirect()->route('explore.index'),
        };
    }
}
