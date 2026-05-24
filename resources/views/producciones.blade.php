@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white/80 p-8 rounded-2xl shadow-2xl">

<div class="flex justify-between items-center mb-6">

<div>

<h2 class="text-3xl font-extrabold">

Producción

</h2>

<p class="text-gray-500">

Crear órdenes y consumir inventario automáticamente

</p>

</div>

<button
onclick="abrirModal()"
class="bg-blue-600 text-white px-5 py-3 rounded-lg">

+ Nueva Producción

</button>

</div>


<table class="w-full">

<thead class="bg-blue-100">

<tr>

<th class="p-3">Código</th>

<th class="p-3">Nombre</th>

<th class="p-3">Cliente</th>

<th class="p-3">Estado</th>

<th class="p-3">Cantidad</th>

<th class="p-3">Acciones</th>

</tr>

</thead>

<tbody id="tablaProducciones">

</tbody>

</table>

</div>



<div
id="modal"
class="hidden fixed inset-0 bg-black/40 flex justify-center items-center">

<div class="bg-white p-8 rounded-xl w-[900px]">

<h3 class="text-xl font-bold mb-5">

Nueva Producción

</h3>


<div class="grid grid-cols-2 gap-4">

<input
id="codigo"
placeholder="Código"
class="border p-2 rounded">


<input
id="nombre"
placeholder="Nombre"
class="border p-2 rounded">


<select
id="persona_id"
class="border p-2 rounded">
</select>


<input
id="fecha_inicio"
type="date"
class="border p-2 rounded">


<input
id="cantidad_prendas"
type="number"
placeholder="Cantidad prendas"
class="border p-2 rounded">


<textarea
id="descripcion"
placeholder="Descripción"
class="border p-2 rounded">
</textarea>

</div>


<hr class="my-5">


<h4 class="font-bold mb-3">

Detalle producción

</h4>


<div class="flex gap-3">

<select
id="tipoDetalle"
class="border p-2">

<option value="consumo">

Consumo material

</option>

<option value="produccion">

Prenda producida

</option>

</select>


<select
id="itemDetalle"
class="border p-2">
</select>


<input
id="cantidadDetalle"
type="number"
placeholder="Cantidad"
class="border p-2">


<button
onclick="agregarDetalle()"
class="bg-green-600 text-white px-4">

Agregar

</button>

</div>


<table class="w-full mt-4">

<thead>

<tr class="bg-gray-100">

<th>Tipo</th>

<th>Item</th>

<th>Cantidad</th>

</tr>

</thead>

<tbody id="tablaDetalles">

</tbody>

</table>


<div class="flex justify-end mt-5 gap-3">

<button
onclick="cerrarModal()">

Cancelar

</button>

<button
onclick="guardarProduccion()"
class="bg-blue-600 text-white px-4 py-2 rounded">

Guardar

</button>

</div>

</div>

</div>

<script>

let detalles=[];

let materiales=[];

let prendas=[];


function abrirModal(){

detalles=[];

tablaDetalles.innerHTML='';

modal.classList.remove(
'hidden'
);

}


function cerrarModal(){

modal.classList.add(
'hidden'
);

}


function cargarClientes(){

fetch('/api/personas')

.then(r=>r.json())

.then(data=>{

persona_id.innerHTML='';

data.forEach(p=>{

persona_id.innerHTML+=`

<option value="${p.id}">

${p.nombre}

</option>

`;

});

});

}


function cargarMateriales(){

fetch('/api/inventario-materiales')

.then(r=>r.json())

.then(data=>{

materiales=data;

cambiarTipo();

});

}


function cargarPrendas(){

fetch('/api/inventario-prendas')

.then(r=>r.json())

.then(data=>{

prendas=data;

cambiarTipo();

});

}


function cambiarTipo(){

itemDetalle.innerHTML='';

if(
tipoDetalle.value==
'consumo'
){

materiales.forEach(m=>{

itemDetalle.innerHTML+=`

<option value="${m.id}">

${m.nombre}

Stock:${m.stock}

</option>

`;

});

}else{

prendas.forEach(p=>{

itemDetalle.innerHTML+=`

<option value="${p.id}">

${p.tipo_prenda}

T:${p.talla}

</option>

`;

});

}

}


tipoDetalle.addEventListener(
'change',
cambiarTipo
);


function agregarDetalle(){

if(!cantidadDetalle.value){
    alert('Ingresa una cantidad');
    return;
}

let obj = {
    tipo: tipoDetalle.value,
    cantidad: parseFloat(cantidadDetalle.value),
    costo_unitario: 0,
    subtotal: 0
};

if(obj.tipo === 'consumo') {

    obj.inventario_material_id = itemDetalle.value;

} else if(obj.tipo === 'produccion') {

    if(!itemDetalle.value){
        alert('Selecciona una prenda');
        return;
    }

    obj.inventario_prenda_id = parseInt(itemDetalle.value);
}

detalles.push(obj);

let nombreSeleccionado =
itemDetalle.options[itemDetalle.selectedIndex].text;

tablaDetalles.innerHTML += `
<tr class="border-b">
<td>${obj.tipo}</td>
<td>${nombreSeleccionado}</td>
<td>${obj.cantidad}</td>
</tr>
`;

}

function guardarProduccion(){

const datos={

codigo:
codigo.value,

nombre:
nombre.value,

descripcion:
descripcion.value,

persona_id:
persona_id.value,

fecha_inicio:
fecha_inicio.value,

cantidad_prendas:
cantidad_prendas.value,

detalles:
detalles

};


fetch(
'/api/producciones',
{

method:'POST',

headers:{

'Content-Type':'application/json',
'Accept':'application/json'

},

body:
JSON.stringify(datos)

})

.then(async r=>{

const data=
await r.json();

if(!r.ok){

console.log(data);

throw new Error(
JSON.stringify(data)
);

}

return data;

})

.then(data=>{

cerrarModal();

cargarProducciones();

})

.catch(error=>{

console.error(
error
);

alert(
'Error al guardar producción'
);

});

}



function cargarProducciones(){

fetch(
'/api/producciones'
)

.then(r=>r.json())

.then(data=>{

tablaProducciones.innerHTML='';


data.forEach(p=>{

tablaProducciones.innerHTML+=`

<tr class="border-b">

<td>${p.codigo}</td>

<td>${p.nombre}</td>

<td>${p.persona?.nombre??'-'}</td>

<td>${p.estado}</td>

<td>${p.cantidad_prendas}</td>

<td>
    ${
        p.estado !== 'finalizada'
        ? `<button
            onclick="finalizarProduccion(${p.id})"
            class="bg-green-600 text-white px-3 py-1 rounded">
            Finalizar
           </button>`
        : `<span class="text-green-700 font-bold">Finalizada</span>`
    }
</td>

</tr>

`;

});

});

}


cargarClientes();

cargarMateriales();

cargarPrendas();

cargarProducciones();

function finalizarProduccion(id) {

if(!confirm('¿Finalizar producción? Esto actualizará inventario')) return;

fetch(`/api/producciones/${id}`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        estado: 'finalizada'
    })
})
.then(r => r.json())
.then(data => {

    console.log('Producción finalizada:', data);

    cargarProducciones(); // refrescar tabla

})
.catch(err => {
    console.error(err);
    alert('Error al finalizar producción');
});

}

</script>

@endsection