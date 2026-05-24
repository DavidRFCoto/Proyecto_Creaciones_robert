@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-2xl">

<div class="flex justify-between items-center mb-6 border-b pb-4">

<div>

<h2 class="text-3xl font-extrabold">
Inventario Prendas
</h2>

<p class="text-gray-500">
Gestión de uniformes y prendas terminadas
</p>

</div>

<button
onclick="abrirModal()"
class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">

+ Nueva Prenda

</button>

</div>


<div class="mb-5">

<input
id="busqueda"
type="text"
placeholder="Buscar prenda..."
oninput="filtrarPrendas()"
class="w-full border rounded-lg p-3">

</div>


<div class="overflow-auto">

<table class="w-full">

<thead class="bg-blue-100">

<tr>

<th class="p-3">Nombre</th>
<th class="p-3">Tipo</th>
<th class="p-3">Talla</th>
<th class="p-3">Color</th>
<th class="p-3">Stock</th>
<th class="p-3">Precio</th>
<th class="p-3">Acciones</th>

</tr>

</thead>

<tbody id="tablaPrendas">

</tbody>

</table>

</div>

</div>



<div
id="modal"
class="hidden fixed inset-0 bg-black/40 flex justify-center items-center">

<div class="bg-white p-8 rounded-xl w-[750px]">

<h3 class="text-xl font-bold mb-5">

Nueva Prenda

</h3>


<div class="grid grid-cols-2 gap-4">

<input
id="nombre"
placeholder="Nombre"
class="border p-2 rounded">


<select
id="tipo_prenda"
class="border p-2 rounded">

<option value="camisa">
Camisa
</option>

<option value="pantalon">
Pantalón
</option>

<option value="falda">
Falda
</option>

</select>


<input
id="categoria"
placeholder="Escolar / Deportivo"
class="border p-2 rounded">


<input
id="talla"
placeholder="Talla"
class="border p-2 rounded">


<select
id="sexo"
class="border p-2 rounded">

<option value="">
Sexo
</option>

<option>
Masculino
</option>

<option>
Femenino
</option>

<option>
Unisex
</option>

</select>


<input
id="color"
placeholder="Color"
class="border p-2 rounded">


<input
id="stock"
type="number"
placeholder="Stock"
class="border p-2 rounded">


<input
id="costo_produccion"
type="number"
step="0.01"
placeholder="Costo"
class="border p-2 rounded">


<input
id="precio_venta"
type="number"
step="0.01"
placeholder="Precio"
class="border p-2 rounded">


<input
id="motivo"
placeholder="Motivo"
class="border p-2 rounded">


<textarea
id="descripcion"
placeholder="Descripción"
class="border p-2 rounded col-span-2">
</textarea>

</div>


<div class="flex justify-end mt-5 gap-3">

<button
onclick="cerrarModal()"
class="px-4 py-2 border">

Cancelar

</button>


<button
onclick="guardarPrenda()"
class="bg-blue-600 text-white px-4 py-2 rounded">

Guardar

</button>

</div>

</div>

</div>


<script>

let prendas=[];

let prendasFiltradas=[];

let editandoId=null;


function abrirModal(){

document
.getElementById('modal')
.classList.remove('hidden');

}


function cerrarModal(){

document
.getElementById('modal')
.classList.add('hidden');

}


function cargarPrendas(){

fetch('/api/inventario-prendas')

.then(r=>r.json())

.then(data=>{

prendas=data;

prendasFiltradas=data;

renderizar();

});

}


function filtrarPrendas(){

const texto=
busqueda.value
.toLowerCase();

prendasFiltradas=
prendas.filter(p=>

(p.nombre ?? '')
.toLowerCase()
.includes(texto)

||

(p.color ?? '')
.toLowerCase()
.includes(texto)

||

(p.tipo_prenda ?? '')
.toLowerCase()
.includes(texto)

);

renderizar();

}


function renderizar(){

tablaPrendas.innerHTML='';

prendasFiltradas.forEach(p=>{

tablaPrendas.innerHTML+=`

<tr class="border-b">

<td class="p-3">${p.nombre}</td>

<td class="p-3">${p.tipo_prenda}</td>

<td class="p-3">${p.talla}</td>

<td class="p-3">${p.color ?? '-'}</td>

<td class="p-3">${p.stock}</td>

<td class="p-3">$${p.precio_venta ?? 0}</td>

<td class="p-3 flex gap-3">

<button
onclick="editar(${p.id})"
class="text-blue-600">

Editar

</button>

<button
onclick="eliminar(${p.id})"
class="text-red-500">

Eliminar

</button>

</td>

</tr>

`;

});

}


function guardarPrenda(){

const datos={

nombre:nombre.value,

tipo_prenda:tipo_prenda.value,

categoria:categoria.value,

talla:talla.value,

sexo:sexo.value,

color:color.value,

stock:parseInt(stock.value),

costo_produccion:
costo_produccion.value
? parseFloat(costo_produccion.value)
: null,

precio_venta:
precio_venta.value
? parseFloat(precio_venta.value)
: null,

motivo:motivo.value,

descripcion:
descripcion.value

};


const url=editandoId

? '/api/inventario-prendas/'+editandoId
: '/api/inventario-prendas';


const metodo=
editandoId
? 'PUT'
: 'POST';


fetch(url,{

method:metodo,

headers:{

'Content-Type':'application/json',
'Accept':'application/json'

},

body:JSON.stringify(datos)

})

.then(async r=>{

const data=
await r.json();

if(!r.ok){

throw new Error(
JSON.stringify(data)
);

}

return data;

})

.then(()=>{

editandoId=null;

cerrarModal();

cargarPrendas();

limpiarFormulario();

})

.catch(error=>{

console.error(error);

alert(
'Error al guardar'
);

});

}



function editar(id){

const p=
prendas.find(
x=>x.id===id
);

if(!p)return;


editandoId=id;


nombre.value=
p.nombre ?? '';

tipo_prenda.value=
p.tipo_prenda ?? '';

categoria.value=
p.categoria ?? '';

talla.value=
p.talla ?? '';

sexo.value=
p.sexo ?? '';

color.value=
p.color ?? '';

stock.value=
p.stock ?? '';

costo_produccion.value=
p.costo_produccion ?? '';

precio_venta.value=
p.precio_venta ?? '';

motivo.value=
p.motivo ?? '';

descripcion.value=
p.descripcion ?? '';

abrirModal();

}


function limpiarFormulario(){

nombre.value='';

categoria.value='';

talla.value='';

sexo.value='';

color.value='';

stock.value='';

costo_produccion.value='';

precio_venta.value='';

motivo.value='';

descripcion.value='';

}



function eliminar(id){

if(!confirm(
'¿Eliminar?'
))return;


fetch(
'/api/inventario-prendas/'+id,
{
method:'DELETE'
})

.then(()=>{

cargarPrendas();

});

}

cargarPrendas();

</script>

@endsection