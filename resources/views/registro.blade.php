<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SIGETEX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #020617);
            background-size: 200% 200%;
            animation: moverFondo 12s ease infinite;
        }
        @keyframes moverFondo { 
            0% { background-position: 0% 50%; } 
            50% { background-position: 100% 50%; } 
            100% { background-position: 0% 50%; } 
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen overflow-hidden py-10">

    <div class="bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-white/20 w-full max-w-sm relative z-10">
        
        <div class="mb-6 text-center">
            <h1 class="text-4xl font-black text-white tracking-widest drop-shadow-lg">SIGETEX</h1>
            <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mt-2">Crear Cuenta</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 text-xs rounded-lg p-3 mb-4 text-center font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.manual') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-white text-xs font-medium mb-1">Nombre Completo</label>
                <input type="text" name="name" id="name" required autofocus
                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all text-sm"
                    placeholder="Ej. Juan Pérez">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-white text-xs font-medium mb-1">Correo Electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all text-sm"
                    placeholder="ejemplo@correo.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-white text-xs font-medium mb-1">Contraseña</label>
                <input type="password" name="password" id="password" required minlength="6"
                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all text-sm"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all duration-300 transform hover:-translate-y-0.5 text-sm">
                Registrar Empleado
            </button>
        </form>

        <div class="flex items-center my-5">
            <div class="flex-grow border-t border-white/20"></div>
            <span class="px-3 text-white/50 text-xs font-medium">O regístrate con</span>
            <div class="flex-grow border-t border-white/20"></div>
        </div>

        <button id="btn-google" type="button" class="w-full flex items-center justify-center gap-3 bg-white text-gray-800 font-bold py-3 px-4 rounded-xl hover:bg-gray-100 transition-transform hover:-translate-y-0.5 shadow-[0_0_15px_rgba(255,255,255,0.2)] text-sm">
            Google
        </button>

        <p class="mt-6 text-center text-xs text-gray-400">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-semibold underline transition-colors">Inicia sesión</a>
        </p>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyBIC4EGSdgSGzeF6eY3Nz17bKT2vbHx0FI",
            authDomain: "creaciones-robert.firebaseapp.com",
            projectId: "creaciones-robert",
            storageBucket: "creaciones-robert.firebasestorage.app",
            messagingSenderId: "168343365681",
            appId: "1:168343365681:web:070ad0a817095870f8cd10"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        document.getElementById('btn-google').addEventListener('click', () => {
            signInWithPopup(auth, provider).then((result) => {
                fetch('/registrar-google', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ email: result.user.email, name: result.user.displayName })
                }).then(res => res.json()).then(data => {
                    if(data.success) window.location.href = data.redirect;
                });
            });
        });
    </script>
</body>
</html>