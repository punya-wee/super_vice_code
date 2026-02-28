<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ─── Show pages ───────────────────────────────────────────

    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    // ─── Login ────────────────────────────────────────────────

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => '/hub']);
            }
            return redirect('/hub');
        }

        $msg = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $msg], 401);
        }
        return back()->withErrors(['email' => $msg])->onlyInput('email');
    }

    // ─── Register ─────────────────────────────────────────────

    public function register(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ], [
                'full_name.required' => 'กรุณากรอกชื่อ-นามสกุล',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'อีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้แล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
                'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            ]);

            User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'role' => 'member',
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => '/login'], 201);
            }
            return redirect('/login')->with('success', 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['email' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()])->withInput();
        }
    }

    // ─── Logout ───────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ─── Google OAuth ─────────────────────────────────────────

    /**
     * Step 1: Redirect ไปหน้า consent ของ Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Step 2: รับข้อมูลจาก Google หลัง user อนุญาต
     *
     * Logic:
     *  - ถ้า email ตรงกับ user ในระบบ → login ทันที (ไม่สนว่าสมัครด้วย email หรือ Google)
     *  - ถ้าไม่มี user → สร้างใหม่ (password_hash = random, user ไม่ได้ใช้ password)
     *
     * ไม่ต้องเพิ่ม column ใหม่ใน users table เพราะใช้ email เป็น unique key
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login ล้มเหลว กรุณาลองใหม่');
        }

        // หา user จาก email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // สร้าง user ใหม่ — ใส่ password_hash แบบ random เพราะ Google user ไม่ได้ login ด้วย password
            $user = User::create([
                'full_name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'password_hash' => Hash::make(Str::random(32)),
                'role' => 'member',
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('hub')->with('success', 'เข้าสู่ระบบด้วย Google สำเร็จ! 🎉');
    }
}
