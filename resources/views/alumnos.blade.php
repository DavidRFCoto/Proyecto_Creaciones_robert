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


        <div class="mb-4 hidden" id="contenedor_colegio">
    <label class="block text-sm font-bold text-gray-700 mb-2">
        Nombre del Colegio *
    </label>

    <input
        type="text"
        id="nombre_colegio"
        class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        placeholder="Ej: Colegio Don Bosco">
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

function cambiarTipo() {
    const tipo = document.getElementById('tipo_registro').value;

    const contenedorGrado = document.getElementById('contenedor_grado');
    const contenedorEmpresa = document.getElementById('contenedor_empresa');
    const contenedorColegio = document.getElementById('contenedor_colegio');

    const inputGrado = document.getElementById('grupo_grado');
    const inputEmpresa = document.getElementById('nombre_empresa');
    const inputColegio = document.getElementById('nombre_colegio');

    const btnGuardar = document.getElementById('btn-guardar');

    // reset visual
    contenedorGrado.classList.add('hidden');
    contenedorEmpresa.classList.add('hidden');
    contenedorColegio.classList.add('hidden');

    // limpiar inputs
    inputGrado.value = '';
    inputEmpresa.value = '';
    inputColegio.value = '';

    // reset botón
    btnGuardar.classList.remove(
        'bg-blue-600',
        'bg-red-600'
    );

    switch(tipo){

        case 'alumno':
            contenedorGrado.classList.remove('hidden');
            contenedorColegio.classList.remove('hidden');
            break;

        case 'empresa':
            contenedorEmpresa.classList.remove('hidden');
            break;

        case 'cliente':
            break;
    }

    btnGuardar.classList.add('bg-blue-600');
}


// VALIDACIONES CENTRALIZADAS
function validarFormulario(tipo){

    const reglas = {

        alumno:[
            {
                valor:document.getElementById('grupo_grado').value,
                mensaje:'Ingresa el grado.'
            },
            {
                valor:document.getElementById('nombre_colegio').value,
                mensaje:'Ingresa el colegio.'
            }
        ],

        empresa:[
            {
                valor:document.getElementById('nombre_empresa').value,
                mensaje:'Ingresa el nombre de la empresa.'
            }
        ],

        cliente:[]
    };


    for(let campo of reglas[tipo]){

        if(campo.valor.trim()===''){
            alert(campo.mensaje);
            return false;
        }

    }

    return true;
}



document
.getElementById('formularioUsuario')
.addEventListener('submit',function(e){

    e.preventDefault();

    const tipo =
    document.getElementById('tipo_registro').value;


    if(!validarFormulario(tipo)){
        return;
    }


    const datos={

        nombre:
        document.getElementById('nombre').value,

        sexo:
        document.getElementById('sexo').value,

        tipo:tipo,

        nombre_empresa:
        tipo==='empresa'
        ? document.getElementById('nombre_empresa').value
        : null,

        nombre_colegio:
        tipo==='alumno'
        ? document.getElementById('nombre_colegio').value
        : null,

        grupo_grado:
        tipo==='alumno'
        ? document.getElementById('grupo_grado').value
        : 'N/A'
    };


    fetch('/api/personas',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json'
        },

        body:JSON.stringify(datos)

    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            document.getElementById('formularioUsuario').reset();

            document
            .getElementById('error')
            .classList.add('hidden');

            document
            .getElementById('resultado')
            .classList.remove('hidden');

            document
            .getElementById('resultado')
            .innerHTML=
            `✅ <strong>${data.persona.nombre}</strong>
            registrado con éxito como
            ${tipo.toUpperCase()}.`;

            setTimeout(()=>{

                window.location.href=
                `/tomar-medidas/${data.persona.id}`;

            },1200);

        }else{

            document
            .getElementById('resultado')
            .classList.add('hidden');

            document
            .getElementById('error')
            .classList.remove('hidden');

            document
            .getElementById('error')
            .innerText=
            "❌ Error al guardar.";

        }

    });

});


// cargar estado inicial
cambiarTipo();

</script>
@endsection