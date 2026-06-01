<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SIGETEX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            /* Fondo corporativo y elegante */
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
<body class="flex items-center justify-center h-screen overflow-hidden">

    <div class="bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-white/20 w-full max-w-sm relative z-10 transform transition-all">
        
        <div class="mb-8 text-center">
            <h1 class="text-5xl font-black text-white tracking-widest drop-shadow-lg">SIGETEX</h1>
            <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mt-2">Creaciones Robert</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 text-sm rounded-lg p-3 mb-6 text-center font-semibold">
                {{ $errors->first('email') }}
            </div>
        @endif

        <p class="text-gray-300 text-sm mb-6 text-center">Inicia sesión para acceder al sistema</p>

        <form method="POST" action="{{ route('login.manual') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-white text-sm font-medium mb-2">Correo Electrónico</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all"
                    placeholder="ejemplo@correo.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-white text-sm font-medium mb-2">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all duration-300 transform hover:-translate-y-0.5">
                Ingresar
            </button>
        </form>

        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-white/20"></div>
            <span class="px-3 text-white/50 text-sm font-medium">O continuar con</span>
            <div class="flex-grow border-t border-white/20"></div>
        </div>

        <button id="btn-google" type="button" class="w-full flex items-center justify-center gap-3 bg-white text-gray-800 font-bold py-3.5 px-4 rounded-xl hover:bg-gray-100 transition-transform hover:-translate-y-0.5 shadow-[0_0_15px_rgba(255,255,255,0.2)]">
            <svg class="w-6 h-6" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
            </svg>
            Google
        </button>

        <div id="mensaje" class="mt-4 text-center text-sm font-bold text-white hidden animate-pulse"></div>
        <p class="mt-6 text-center text-xs text-gray-400">
            ¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-blue-400 hover:text-blue-300 font-semibold underline transition-colors">Regístrate aquí</a>
        </p>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

        // Tus llaves reales de Creaciones Robert
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
            const mensaje = document.getElementById('mensaje');
            mensaje.innerText = "Conectando con Google...";
            mensaje.classList.remove('hidden', 'text-red-400');
            mensaje.classList.add('text-blue-300');

            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;
                    mensaje.innerText = "¡Verificado! Accediendo a SIGETEX...";
                    mensaje.classList.replace('text-blue-300', 'text-emerald-400');
                    
                    // Envía los datos a tu controlador
                    fetch('/iniciar-sesion-google', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            email: user.email,
                            name: user.displayName 
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            window.location.href = data.redirect;
                        } else {
                            mensaje.innerText = data.message || "Usuario no autorizado en el sistema.";
                            mensaje.classList.replace('text-emerald-400', 'text-red-400');
                        }
                    });
                })
                .catch((error) => {
                    console.error("Error de Firebase:", error);
                    mensaje.innerText = "Se canceló o falló el inicio de sesión.";
                    mensaje.classList.replace('text-blue-300', 'text-red-400');
                });
        });
    </script>
</body>
</html>