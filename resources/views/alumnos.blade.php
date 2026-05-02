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
                <option value="empresa">Empresa</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo *</label>
            <input type="text" id="nombre" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none" required autocomplete="off">
        </div>

        <div class="mb-4 hidden" id="contenedor_empresa">
             <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de la Empresa *</label>
            <input type="text" id="nombre_empresa" class="w-full border-gray-300 rounded-lg p-2.5">
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
    const contenedorEmpresa = document.getElementById('contenedor_empresa');

    const inputGrado = document.getElementById('grupo_grado');
    const inputEmpresa = document.getElementById('nombre_empresa');

    const btnGuardar = document.getElementById('btn-guardar');

    // Reset (esto siempre se ejecuta)
    contenedorGrado.classList.add('hidden');
    contenedorEmpresa.classList.add('hidden');

    inputGrado.value = '';
    inputEmpresa.value = '';

    btnGuardar.classList.remove('bg-blue-600', 'bg-red-600');

    // Lógica exclusiva
    if (tipo === 'alumno') {
        contenedorGrado.classList.remove('hidden');
        btnGuardar.classList.add('bg-blue-600');

    } else if (tipo === 'empresa') {
        contenedorEmpresa.classList.remove('hidden');
        btnGuardar.classList.add('bg-blue-600');

    } else {
        // cliente
        btnGuardar.classList.add('bg-blue-600');
    }
}


    // Lógica para enviar el formulario a la base de datos
    document.getElementById('formularioUsuario').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const tipo = document.getElementById('tipo_registro').value;

    let empresaValue = document.getElementById('nombre_empresa').value;
    let gradoValue = document.getElementById('grupo_grado').value;

    // Validaciones
    if (tipo === 'alumno' && gradoValue.trim() === '') {
        alert("Ingresa el grado.");
        return;
    }

    if (tipo === 'empresa' && empresaValue.trim() === '') {
        alert("Ingresa el nombre de la empresa.");
        return;
    }

    const datos = {
        nombre: document.getElementById('nombre').value,
        sexo: document.getElementById('sexo').value,
        tipo: tipo,
        nombre_empresa: tipo === 'empresa' ? empresaValue : null,
        grupo_grado: tipo === 'alumno' ? gradoValue : 'N/A'
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
            
                 setTimeout(() => {
                window.location.href = `/tomar-medidas/${data.persona.id}`;
            }, 1200);
            
            } else {
                document.getElementById('resultado').classList.add('hidden');
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('error').innerText = "❌ Error al guardar. Verifica los datos.";
            }
        });
    });
</script>
@endsection