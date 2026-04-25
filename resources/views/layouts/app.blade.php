<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGETEX - Creaciones Robert</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animación directa en el cuerpo de la página */
        body {
            background-size: 300% 300%;
            animation: moverFondo 15s ease infinite;
            transition: background 0.8s ease-in-out; 
        }
        
        .tema-azul { background: linear-gradient(120deg, #0f172a, #1e3a8a, #312e81, #0f172a); }
        .tema-rojo { background: linear-gradient(120deg, #450a0a, #991b1b, #7f1d1d, #450a0a); }

        @keyframes moverFondo {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body id="cuerpo-sistema" class="font-sans text-gray-800 flex h-screen overflow-hidden tema-azul">

    <aside class="w-64 flex flex-col shadow-2xl z-10 bg-white/10 backdrop-blur-lg border-r border-white/20">
        <div class="p-6 text-center border-b border-white/20 mt-4">
            <h1 class="text-3xl font-black tracking-widest text-white drop-shadow-lg">SIGETEX</h1>
            <p class="text-xs text-white/80 mt-2 uppercase tracking-wide font-bold">Creaciones Robert</p>
        </div>
        
        <nav class="flex-1 px-4 py-8 space-y-3 text-white">
            <a href="/registrar-alumno" class="block px-4 py-3 rounded-lg hover:bg-white/20 transition-colors {{ request()->is('registrar-alumno') ? 'bg-white/30 border-l-4 border-white font-bold shadow-inner' : '' }}">👤 Registrar Usuario</a>
            <a href="/tomar-medidas" class="block px-4 py-3 rounded-lg hover:bg-white/20 transition-colors {{ request()->is('tomar-medidas') ? 'bg-white/30 border-l-4 border-white font-bold shadow-inner' : '' }}">📝 Tomar Medidas</a>
            <a href="/listado-medidas" class="block px-4 py-3 rounded-lg hover:bg-white/20 transition-colors {{ request()->is('listado-medidas') ? 'bg-white/30 border-l-4 border-white font-bold shadow-inner' : '' }}">📂 Historial de Tallas</a>
        </nav>

        <div class="p-4 text-center text-xs text-white/60 border-t border-white/20">
            SIGETEX v1.0<br>Panel de Administración
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 relative z-0">
        @yield('content')
    </main>

</body>
</html>