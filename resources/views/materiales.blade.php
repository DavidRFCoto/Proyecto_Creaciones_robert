@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-2xl">

    <div class="flex justify-between items-center mb-6 border-b pb-4">

        <div>
            <h2 class="text-3xl font-extrabold">
                Inventario Materiales
            </h2>

            <p class="text-gray-500">
                Gestión de tela, botones, hilos y materia prima
            </p>
        </div>

        <button
            onclick="abrirModal()"
            class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">

            + Nuevo Material

        </button>

    </div>

 <!-- BUSCADOR -->  
    <div class="mb-5">

<input
id="busqueda"
type="text"
placeholder="Buscar material..."
oninput="filtrarMateriales()"
class="w-full border rounded-lg p-3">

</div>


    <div class="overflow-auto">

        <table class="w-full">

            <thead class="bg-blue-100">

                <tr>

                    <th class="p-3">Color</th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Categoría</th>
                    <th class="p-3">Stock</th>
                    <th class="p-3">Compra</th>
                    <th class="p-3">Proveedor</th>
                    <th class="p-3">Acciones</th>

                </tr>

            </thead>

            <tbody id="tablaMateriales">

            </tbody>

        </table>

    </div>

</div>



<!-- MODAL -->

<div
id="modal"
class="hidden fixed inset-0 bg-black/40 flex justify-center items-center">

<div class="bg-white p-8 rounded-xl w-[700px]">

<h3 class="text-xl font-bold mb-5">

Nuevo Material

</h3>


<div class="grid grid-cols-2 gap-4">

<input
id="nombre"
placeholder="Nombre"
class="border p-2 rounded">

<select
id="categoria"
class="border p-2 rounded">

<option>Tela</option>
<option>Botón</option>
<option>Hilo</option>
<option>Cierre</option>
<option>Elástico</option>
<option>Otro</option>

</select>

<input
id="color"
placeholder="Color"
class="border p-2 rounded">

<input
id="marca"
placeholder="Marca"
class="border p-2 rounded">

<input
id="unidad_medida"
placeholder="Unidad (metros, yardas...)"
class="border p-2 rounded">

<input
id="stock"
type="number"
placeholder="Stock"
class="border p-2 rounded">

<input
id="stock_minimo"
type="number"
placeholder="Stock mínimo"
class="border p-2 rounded">

<input
id="precio_compra"
type="number"
step="0.01"
placeholder="Precio compra"
class="border p-2 rounded">

<input
id="precio_venta"
type="number"
step="0.01"
placeholder="Precio venta"
class="border p-2 rounded">

<input
id="proveedor"
placeholder="Proveedor"
class="border p-2 rounded">

<input
id="lote"
placeholder="Lote"
class="border p-2 rounded">

<input
id="fecha_ingreso"
type="date"
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
onclick="guardarMaterial()"
class="bg-blue-600 text-white px-4 py-2 rounded">

Guardar

</button>

</div>

</div>

</div>


<script>

let materiales=[];
let materialesFiltrados=[];
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


function cargarMateriales(){

fetch('/api/inventario-materiales')

.then(r=>r.json())

.then(data=>{materiales=data; materialesFiltrados=data;

renderizar();

});

}


function filtrarMateriales(){

const texto=
document
.getElementById('busqueda')
.value
.toLowerCase();

materialesFiltrados=
materiales.filter(m=>

(m.nombre ?? '')
.toLowerCase()
.includes(texto)

||

(m.categoria ?? '')
.toLowerCase()
.includes(texto)

||

(m.proveedor ?? '')
.toLowerCase()
.includes(texto)

||

(m.color ?? '')
.toLowerCase()
.includes(texto)

);

renderizar();

}




function renderizar(){

const cuerpo=
document.getElementById(
'tablaMateriales'
);

cuerpo.innerHTML='';


materialesFiltrados.forEach(m=>{

cuerpo.innerHTML+=`

<tr class="border-b">

<td class="p-3">
${m.color ?? '-'}
</td>

<td class="p-3">${m.nombre}</td>

<td class="p-3">${m.categoria}</td>

<td class="p-3">

${m.stock}

</td>

<td class="p-3">
$${m.precio_compra ?? 0}
</td>

<td class="p-3">

${m.proveedor??'-'}

</td>

<td class="p-3 flex gap-3">

<button
onclick="editar(${m.id})"
class="text-blue-600">

Editar

</button>

<button
onclick="eliminar(${m.id})"
class="text-red-500">

Eliminar

</button>

</td>

</tr>

`;

});


}

function editar(id){

const material=
materiales.find(
m=>m.id===id
);

editandoId=id;

nombre.value=
material.nombre ?? '';

categoria.value=
material.categoria ?? '';

color.value=
material.color ?? '';

marca.value=
material.marca ?? '';

stock.value=
material.stock ?? '';

unidad_medida.value=
material.unidad_medida ?? '';

stock_minimo.value=
material.stock_minimo ?? '';

precio_compra.value=
material.precio_compra ?? '';

precio_venta.value=
material.precio_venta ?? '';

proveedor.value=
material.proveedor ?? '';

lote.value=
material.lote ?? '';

fecha_ingreso.value=
material.fecha_ingreso ?? '';

descripcion.value=
material.descripcion ?? '';

abrirModal();

}


function guardarMaterial() {

const datos = {

nombre: nombre.value,

categoria: categoria.value,

color: color.value,

marca: marca.value,

stock: parseFloat(
    stock.value
),

unidad_medida:
unidad_medida.value,

stock_minimo:
parseFloat(
stock_minimo.value
),

precio_compra:
precio_compra.value
? parseFloat(
precio_compra.value
)
: null,

precio_venta:
precio_venta.value
? parseFloat(
precio_venta.value
)
: null,

proveedor:
proveedor.value,

lote:
lote.value,

fecha_ingreso:
fecha_ingreso.value,

descripcion:
descripcion.value

};


const url = editandoId

? '/api/inventario-materiales/' + editandoId

: '/api/inventario-materiales';


const metodo=

editandoId

? 'PUT'

: 'POST';



fetch(url, {

method:metodo,

headers:{
'Content-Type':'application/json',
'Accept':'application/json'
},

body:
JSON.stringify(
datos
)

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



editandoId=null;

cerrarModal();

cargarMateriales();

})

.catch(error=>{

console.error(
'ERROR:',
error
);

alert(
'Error al guardar. Revisa consola.'
);

});

}







function eliminar(id){

if(
!confirm(
'¿Eliminar?'
)
)return;


fetch(
'/api/inventario-materiales/'+id,
{
method:'DELETE'
})

.then(()=>{

cargarMateriales();

});

}


cargarMateriales();

</script>

@endsection