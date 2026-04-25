@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/20 mt-8">
    
    <div class="border-b border-gray-300 pb-4 mb-6">
        <h2 class="text-3xl font-extrabold text-gray-800">Registrar Nuevo Usuario</h2>
        <p class="text-gray-600 text-sm mt-1">Selecciona el tipo y completa los datos antes de tomar las medidas.</p>
    </div>
    
    <div id="resultado" class="hidden mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r font-medium"></div>
    <div id="error" class="hidden mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r font-medium"></div>

    <form id="formularioUsuario">
        
        <div class="mb-6 p-4 bg-blue-50/50 rounded-lg border border-blue-100">
            <label class="block text-sm font-bold text-blue-900 mb-2">¿Seleccione tipo de usuario? *</label>
            <select id="tipo_registro" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none" required onchange="cambiarTipo()">
                <option value="alumno">Estudiante</option>
                <option value="cliente">Cliente Particular</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo *</label>
            <input type="text" id="nombre" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none" required autocomplete="off">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Sexo *</label>
                <select id="sexo" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="">Seleccione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            
            <div id="contenedor_grado">
                <label class="block text-sm font-bold text-gray-700 mb-2">Grupo / Grado *</label>
                <input type="text" id="grupo_grado" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Ej: 9no A">
            </div>
        </div>

        <button type="submit" id="btn-guardar" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 shadow-md transition duration-200">
            Guardar Registro
        </button>
    </form>
</div>

<script>
    // Lógica para mostrar/ocultar el campo de grado
    function cambiarTipo() {
        const tipo = document.getElementById('tipo_registro').value;
        const contenedorGrado = document.getElementById('contenedor_grado');
        const inputGrado = document.getElementById('grupo_grado');
        const btnGuardar = document.getElementById('btn-guardar');

        if (tipo === 'cliente') {
            // Esconder el campo y quitarle el "requerido"
            contenedorGrado.classList.add('hidden');
            inputGrado.value = ''; // Lo limpiamos por si acaso
            
            // Un toque visual: Cambiar el botón a rojo
            btnGuardar.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            btnGuardar.classList.add('bg-red-600', 'hover:bg-red-700');
        } else {
            // Mostrar el campo
            contenedorGrado.classList.remove('hidden');
            
            // Regresar el botón a azul
            btnGuardar.classList.remove('bg-red-600', 'hover:bg-red-700');
            btnGuardar.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    }

    // Lógica para enviar el formulario a la base de datos
    document.getElementById('formularioUsuario').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const tipo = document.getElementById('tipo_registro').value;
        let gradoValue = document.getElementById('grupo_grado').value;

        // Validar que el alumno sí tenga grado escrito
        if(tipo === 'alumno' && gradoValue.trim() === '') {
            alert("Por favor, ingresa el grado del alumno.");
            return;
        }

        const datos = {
            nombre: document.getElementById('nombre').value,
            sexo: document.getElementById('sexo').value,
            // Si el selector está en cliente, forzamos el 'N/A'. Si no, mandamos lo que escribió.
            grupo_grado: tipo === 'cliente' ? 'N/A' : gradoValue 
        };

        fetch('/api/personas', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Limpiar formulario y mostrar éxito
                document.getElementById('nombre').value = '';
                if(tipo === 'alumno') document.getElementById('grupo_grado').value = '';
                
                document.getElementById('error').classList.add('hidden');
                document.getElementById('resultado').classList.remove('hidden');
                document.getElementById('resultado').innerHTML = `✅ <strong>${data.persona.nombre}</strong> registrado con éxito como ${tipo.toUpperCase()}. Ya puedes ir a tomarle las medidas.`;
            } else {
                document.getElementById('resultado').classList.add('hidden');
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('error').innerText = "❌ Error al guardar. Verifica los datos.";
            }
        });
    });
</script>
@endsection