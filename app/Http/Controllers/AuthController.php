<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // បង្ហាញ View ផ្ទាំង Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ពិនិត្យទិន្នន័យពេលចុច Login
    public function login(Request $request)
    {
        // ផ្ទៀងផ្ទាត់ទិន្នន័យដែលវាយបញ្ចូល
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // ឆែកមើលក្នុង Database ថាត្រឹមត្រូវទេ
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // បែងចែកសិទ្ធិ (Roles) បន្ទាប់ពី Login ចូល
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard'); // ទៅទំព័រ Admin
            } else {
                return redirect('/pos'); // ទៅទំព័រគិតលុយ Cashier
            }
        }

        // ប្រសិនបើខុស ឱ្យត្រលប់ក្រោយវិញព្រមទាំងបង្ហាញសារ Error
        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ!',
        ])->onlyInput('email');
    }

    // ចាកចេញពីគណនី (Logout)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}