@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-extrabold text-gray-800">Ingreso de Nueva Medida</h2>
            <p class="text-gray-500 text-sm mt-1">Selecciona al alumno y registra sus tallas.</p>
        </div>
        
        <div id="resultado" class="hidden mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r font-medium"></div>
        <div id="error" class="hidden mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r font-medium"></div>

        <form id="formularioMedidas">
            
            <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <label class="block text-sm font-bold text-blue-900 mb-2">Seleccionar Alumno/Cliente *</label>
                <select id="persona_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white" required>
                    <option value="">Cargando lista de alumnos...</option>
                </select>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-3 border-b pb-2">Prendas Superiores</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Hombro (cm) *</label>
                    <input type="number" step="0.1" id="hombro" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Pecho (cm)</label>
                    <input type="number" step="0.1" id="pecho" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Largo Camisa (cm)</label>
                    <input type="number" step="0.1" id="largo_camisa" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-3 border-b pb-2">Prendas Inferiores</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div><label class="block text-sm text-gray-600 mb-1">Cintura (cm) *</label><input type="number" step="0.1" id="cintura" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required></div>
                <div><label class="block text-sm text-gray-600 mb-1">Cadera (cm)</label><input type="number" step="0.1" id="cadera" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm text-gray-600 mb-1">Rodilla (cm)</label><input type="number" step="0.1" id="rodilla" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm text-gray-600 mb-1">Ruedo (cm)</label><input type="number" step="0.1" id="ruedo" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm text-gray-600 mb-1">Largo Pantalón</label><input type="number" step="0.1" id="largo_pantalon" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm text-gray-600 mb-1">Largo Falda</label><input type="number" step="0.1" id="largo_falda" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></div>
            </div>

            <button type="submit" class="w-full bg-blue-700 text-white font-bold py-3 rounded-lg hover:bg-blue-800 transition duration-200">
                Procesar y Guardar Medidas
            </button>
        </form>
    </div>

    <script>
        // Función auxiliar para obtener valores numéricos
        const getVal = id => { let v = document.getElementById(id).value; return v ? parseFloat(v) : null; };

        // 1. CARGAR LA LISTA DE ALUMNOS AL ABRIR LA PÁGINA
        function cargarAlumnos() {
            fetch('/api/personas')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('persona_id');
                    // Limpiamos el select y ponemos una opción por defecto
                    select.innerHTML = '<option value="">-- Seleccione un alumno --</option>';
                    
                    // Llenamos el menú con los nombres que vienen de la base de datos
                    data.forEach(alumno => {
                        select.innerHTML += `<option value="${alumno.id}">${alumno.nombre} (${alumno.grupo_grado})</option>`;
                    });
                })
                .catch(err => console.error("Error cargando alumnos:", err));
        }

        // Ejecutamos la función de carga inmediatamente
        cargarAlumnos();

        // 2. ENVIAR EL FORMULARIO
        document.getElementById('formularioMedidas').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const datos = {
                persona_id: document.getElementById('persona_id').value, // Ahora es un texto/valor del select
                hombro: getVal('hombro'), pecho: getVal('pecho'), largo_camisa: getVal('largo_camisa'), 
                cintura: getVal('cintura'), cadera: getVal('cadera'), rodilla: getVal('rodilla'), 
                ruedo: getVal('ruedo'), largo_pantalon: getVal('largo_pantalon'), largo_falda: getVal('largo_falda')
            };

            fetch('/api/medidas', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(datos)
            }).then(r => r.json()).then(data => {
                if(data.success) {
                    document.getElementById('formularioMedidas').reset();
                    document.getElementById('error').classList.add('hidden');
                    document.getElementById('resultado').classList.remove('hidden');
                    document.getElementById('resultado').innerHTML = `<strong>¡Éxito!</strong><br> Alumno: ${data.alumno} <br> ${data.perfil}`;
                } else {
                    document.getElementById('resultado').classList.add('hidden');
                    document.getElementById('error').classList.remove('hidden');
                    document.getElementById('error').innerText = data.message ? "❌ " + data.message : "❌ Error.";
                }
            });
        });
    </script>
@endsection