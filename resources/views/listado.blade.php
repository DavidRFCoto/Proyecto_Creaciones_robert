@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/20 mt-2 transition-all duration-500">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b border-gray-300 pb-4">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Directorio de Medidas</h2>
        <div class="mt-4 md:mt-0 w-full md:w-1/3">
            <input type="text" id="buscador" placeholder="🔍 Buscar nombre en tiempo real..." class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white/90 shadow-inner transition-all">
        </div>
    </div>

    <div class="flex space-x-4 mb-6">
        <button onclick="cambiarPestaña('alumnos')" id="btn-alumnos" class="px-6 py-2 rounded-lg font-bold transition-all bg-blue-600 text-white shadow-md hover:bg-blue-700">
            🎓 Ver Estudiantes
        </button>
        <button onclick="cambiarPestaña('clientes')" id="btn-clientes" class="px-6 py-2 rounded-lg font-bold transition-all bg-white/50 backdrop-blur-sm text-gray-800 hover:bg-white/70">
            👤 Ver Clientes Particulares
        </button>
        <button onclick="cambiarPestaña('empresas')" id="btn-empresas" class="px-6 py-2 rounded-lg font-bold transition-all bg-white/50 backdrop-blur-sm text-gray-800 hover:bg-white/70">
            🏢 Ver Empresas
        </button>
    </div>

    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 bg-white/90">
        <table class="w-full text-left border-collapse">
            <thead id="tablaCabecera" class="bg-blue-100 text-blue-900 transition-colors duration-300">
                <tr class="uppercase text-xs font-bold border-b border-gray-200">
                    <th class="p-4">Nombre Completo</th>
                    <th class="p-4">Institucion / Grado</th>
                    <th class="p-4">Tallas Sugeridas</th>
                    <th class="p-4">Fecha</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaCuerpo">
            </tbody>
        </table>
    </div>
</div>

<script>
    let todasLasMedidas = [];
    let pestañaActual = 'alumnos';

    function cargarMedidas() {
        fetch('/api/medidas')
            .then(res => res.json())
            .then(data => {
                todasLasMedidas = data;
                renderizarTabla();
            });
    }

    function cambiarPestaña(tipo) {
        pestañaActual = tipo;
        const btnAlumnos = document.getElementById('btn-alumnos');
        const btnClientes = document.getElementById('btn-clientes');
        const btnEmpresas = document.getElementById('btn-empresas');

        const cabecera = document.getElementById('tablaCabecera');
        //const cuerpoSistema = document.getElementById('cuerpo-sistema'); 

         // Reset botones
    btnAlumnos.className = "px-6 py-2 rounded-lg font-bold bg-white/50 text-gray-800";
    btnClientes.className = "px-6 py-2 rounded-lg font-bold bg-white/50 text-gray-800";
    btnEmpresas.className = "px-6 py-2 rounded-lg font-bold bg-white/50 text-gray-800";

    if(tipo === 'alumnos') {
        btnAlumnos.className = "px-6 py-2 rounded-lg font-bold bg-blue-600 text-white";
        cabecera.className = "bg-blue-100 text-blue-900";
    } 
    else if(tipo === 'clientes') {
        btnClientes.className = "px-6 py-2 rounded-lg font-bold bg-red-600 text-white";
        cabecera.className = "bg-red-100 text-red-900";
    } 
    else if(tipo === 'empresas') {
        btnEmpresas.className = "px-6 py-2 rounded-lg font-bold bg-purple-600 text-white";
        cabecera.className = "bg-purple-100 text-purple-900";
    }
        renderizarTabla();
    }

    document.getElementById('buscador').addEventListener('input', renderizarTabla);

    function renderizarTabla() {
    const busqueda = document.getElementById('buscador').value.toLowerCase();
    const cuerpo = document.getElementById('tablaCuerpo');
    cuerpo.innerHTML = '';

    let datosFiltrados = todasLasMedidas.filter(function(m) {
        const nombre = m.persona ? m.persona.nombre.toLowerCase() : '';
        const coincideBusqueda = nombre.includes(busqueda);
        const tipo = m.persona ? m.persona.tipo : null;

        if (pestañaActual === 'alumnos') {
            return tipo === 'alumno' && coincideBusqueda;
        }
        if (pestañaActual === 'clientes') {
            return tipo === 'cliente' && coincideBusqueda;
        }
        if (pestañaActual === 'empresas') {
            return tipo === 'empresa' && coincideBusqueda;
        }

        return false;
    });

    datosFiltrados.forEach(function(m) {

        let nombre = m.persona ? m.persona.nombre : 'N/A';

        let info = '-';
        if (m.persona) {
            if (m.persona.tipo === 'empresa') {
                info = m.persona.nombre_empresa;
            } else {
                info = m.persona.grupo_grado;
            }
        }

        cuerpo.innerHTML += 
            '<tr class="border-b border-gray-100">' +
                '<td class="p-4 font-bold">' + nombre + '</td>' +
                '<td class="p-4 text-sm">' + info + '</td>' +
                '<td class="p-4 text-sm">' + m.talla_sugerida + '</td>' +
                '<td class="p-4 text-xs">' + new Date(m.created_at).toLocaleDateString() + '</td>' +
                '<td class="p-4 text-center">' +
                    '<button onclick="eliminarMedida(' + m.id + ')" class="text-red-500 text-xs">Borrar</button>' +
                '</td>' +
            '</tr>';
    });

    if(datosFiltrados.length === 0) {
        cuerpo.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">No hay registros.</td></tr>';
    }
}


    function eliminarMedida(id) {
        if(!confirm('¿Seguro que deseas eliminar permanentemente este registro?')) return;
        fetch('/api/medidas/' + id, { method: 'DELETE' })
            .then(() => cargarMedidas());
    }

    cargarMedidas();
</script>
@endsection