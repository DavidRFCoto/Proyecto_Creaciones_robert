@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-2xl">

<div class="flex justify-between mb-6">

<div>

<h2 class="text-3xl font-bold">

Movimientos de Inventario

</h2>

<p class="text-gray-500">

Entradas, salidas y ajustes

</p>

</div>

<button
onclick="abrirModal()"
class="bg-blue-600 text-white px-5 py-3 rounded">

+ Nuevo Movimiento

</button>

</div>


<table class="w-full">

<thead class="bg-blue-100">

<tr>

<th class="p-3">
Material
</th>

<th class="p-3">
Tipo
</th>

<th class="p-3">
Cantidad
</th>

<th class="p-3">
Anterior
</th>

<th class="p-3">
Nuevo
</th>

<th class="p-3">
Motivo
</th>

</tr>

</thead>

<tbody id="tabla">

</tbody>

</table>

</div>



<div
id="modal"
class="hidden fixed inset-0 bg-black/40 flex justify-center items-center">

<div class="bg-white p-8 rounded-xl w-[700px]">

<h3 class="font-bold text-xl mb-5">

Nuevo Movimiento

</h3>


<div class="grid grid-cols-2 gap-4">


<select
id="inventario_material_id"
class="border p-2 rounded">
</select>


<select
id="tipo"
class="border p-2 rounded">

<option value="entrada">

Entrada

</option>

<option value="salida">

Salida

</option>

<option value="ajuste">

Ajuste

</option>

</select>


<input
id="cantidad"
type="number"
placeholder="Cantidad"
class="border p-2 rounded">


<input
id="motivo"
placeholder="Motivo"
class="border p-2 rounded">


<input
id="referencia"
placeholder="Referencia"
class="border p-2 rounded">


<textarea
id="descripcion"
placeholder="Descripción"
class="border p-2 rounded">
</textarea>


</div>


<div class="flex justify-end mt-5 gap-3">

<button
onclick="cerrarModal()">

Cancelar

</button>

<button
onclick="guardarMovimiento()"
class="bg-blue-600 text-white px-4 py-2 rounded">

Guardar

</button>

</div>

</div>

</div>

<script>

let materiales=[];


function abrirModal(){

modal.classList.remove(
'hidden'
);

}


function cerrarModal(){

modal.classList.add(
'hidden'
);

}


function cargarMateriales(){

fetch(
'/api/inventario-materiales'
)

.then(r=>r.json())

.then(data=>{

materiales=data;


inventario_material_id
.innerHTML='';


data.forEach(m=>{

inventario_material_id
.innerHTML+=`

<option value="${m.id}">

${m.nombre}

(Stock:${m.stock})

</option>

`;

});

});

}



function cargarMovimientos(){

fetch(
'/api/movimientos-materiales'
)

.then(r=>r.json())

.then(data=>{

tabla.innerHTML='';


data.forEach(m=>{

tabla.innerHTML+=`

<tr class="border-b">

<td class="p-3">

${m.material.nombre}

</td>

<td class="p-3">

${m.tipo}

</td>

<td class="p-3">

${m.cantidad}

</td>

<td class="p-3">

${m.stock_anterior}

</td>

<td class="p-3">

${m.stock_nuevo}

</td>

<td class="p-3">

${m.motivo}

</td>

</tr>

`;

});

});

}



function guardarMovimiento(){

const datos={

inventario_material_id:
inventario_material_id.value,

tipo:
tipo.value,

cantidad:
parseFloat(
cantidad.value
),

motivo:
motivo.value,

referencia:
referencia.value,

descripcion:
descripcion.value

};


fetch(
'/api/movimientos-materiales',
{

method:'POST',

headers:{

'Content-Type':'application/json',
'Accept':'application/json'

},

body:
JSON.stringify(datos)

})

.then(()=>{

cerrarModal();

cargarMovimientos();

cargarMateriales();

});

}


cargarMateriales();

cargarMovimientos();

</script>

@endsection