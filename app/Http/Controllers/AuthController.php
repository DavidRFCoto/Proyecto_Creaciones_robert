<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController
{
    // ==========================================
    // 1. LOGIN MANUAL (Correo y Contraseña)
    // ==========================================
    public function login(Request $request)
    {
        // Validamos que vengan los datos obligatorios
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt hace la magia: busca el correo y compara la contraseña encriptada
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirigimos al sistema
            return redirect('/listado-medidas');
        }

        // Si la contraseña está mal o el correo no existe, lo rebotamos
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // ==========================================
    // 2. LOGIN CON GOOGLE (Firebase)
    // ==========================================
    public function loginGoogle(Request $request)
    {
        // Recibimos el correo que nos manda Firebase desde el Frontend
        $request->validate([
            'email' => 'required|email',
        ]);

        // Buscamos si ese correo está registrado en la base de datos
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Si el usuario existe, forzamos el inicio de sesión
            Auth::login($user);
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'redirect' => '/listado-medidas'
            ]);
        }

        // Si el correo no existe en la BD
        return response()->json([
            'success' => false,
            'message' => 'Este correo no tiene permisos para acceder al sistema.'
        ], 401);
    }

    // ==========================================
    // 3. CERRAR SESIÓN
    // ==========================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ==========================================
    // 4. REGISTRO MANUAL
    // ==========================================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Validamos que el correo no exista ya en la tabla users
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Creamos el usuario en la BD encriptando su contraseña
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Lo logueamos automáticamente y lo mandamos al sistema
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/listado-medidas');
    }

    // ==========================================
    // 5. REGISTRO CON GOOGLE
    // ==========================================
    public function registerGoogle(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string'
        ]);

        // Verificamos si el usuario ya existe
        $user = User::where('email', $request->email)->first();

        // Si no existe, lo creamos
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                // Le asignamos una contraseña aleatoria porque siempre entrará con Google
                'password' => bcrypt(str()->random(16)), 
            ]);
        }

        // Lo logueamos y lo dejamos pasar
        Auth::login($user);
        $request->session()->regenerate();
        
        return response()->json([
            'success' => true,
            'redirect' => '/listado-medidas'
        ]);
    }
}