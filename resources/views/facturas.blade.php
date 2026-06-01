@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow">

    <div class="flex justify-between mb-6">

        <h2 class="text-3xl font-bold">
            Facturación
        </h2>

        <button
            onclick="abrirModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded">

            Nueva Factura

        </button>

    </div>

    <table class="w-full">

        <thead class="bg-blue-100">

            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>

        </thead>

        <tbody id="tablaFacturas">

        </tbody>

    </table>

</div>


<!-- Modal para crear nueva factura -->
<div
id="modal"class="hidden fixed inset-0 z-50 bg-black/40">


<div class="bg-white p-8 rounded-xl w-[1000px]">

<h3 class="text-xl font-bold mb-4">

Nueva Factura

</h3>



<div class="grid grid-cols-2 gap-4">

<input
id="numero"
placeholder="Número factura"
class="border p-2 rounded">

<select
id="persona_id"
class="border p-2 rounded">
</select>

<input
id="fecha"
type="date"
class="border p-2 rounded">

<input
id="descuento"
type="number"
value="0"
class="border p-2 rounded">

</div>





<hr class="my-5">

<h4 class="font-bold mb-3">

Detalle factura

</h4>

<div class="flex gap-3">

<select
id="prendaDetalle"
class="border p-2">
</select>

<input
id="cantidadDetalle"
type="number"
placeholder="Cantidad"
class="border p-2">

<input
id="precioDetalle"
type="number"
placeholder="Precio"
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

<th>Prenda</th>

<th>Cantidad</th>

<th>Precio</th>

<th>Subtotal</th>

</tr>

</thead>

<tbody id="tablaDetalles">

</tbody>

</table>







<div class="mt-5 text-right">

<p>

Subtotal:
<span id="subtotalVista">

0.00

</span>

</p>

</div>






<div class="flex justify-end mt-5 gap-3">

<button
onclick="cerrarModal()">

Cancelar

</button>

<button
onclick="guardarFactura()"
class="bg-blue-600 text-white px-4 py-2 rounded">

Guardar

</button>

</div>

</div>
</div>




<script>

let detalles = [];

let prendas = [];

function abrirModal(){

    detalles = [];
    tablaDetalles.innerHTML = '';

    document.getElementById('modal')
        .classList.remove('hidden');
}

function cerrarModal(){

    document.getElementById('modal')
        .classList.add('hidden');
}



function cargarClientes(){

    fetch('/api/personas')
    .then(r => r.json())
    .then(data => {

        persona_id.innerHTML = '';

        data.forEach(cliente => {

            persona_id.innerHTML += `
                <option value="${cliente.id}">
                    ${cliente.nombre}
                </option>
            `;

        });

    });

}

function cargarPrendas(){

    fetch('/api/inventario-prendas')
    .then(r => r.json())
    .then(data => {

        prendas = data;

        prendaDetalle.innerHTML = '';

        data.forEach(prenda => {

            prendaDetalle.innerHTML += `
                <option value="${prenda.id}">
                    ${prenda.tipo_prenda}
                    - Talla ${prenda.talla}
                    - Stock ${prenda.stock}
                </option>
            `;

        });

    });

}




function agregarDetalle(){

    if(
        !cantidadDetalle.value ||
        !precioDetalle.value
    ){
        alert('Completa cantidad y precio');
        return;
    }

    let prenda = prendas.find(
        p => p.id == prendaDetalle.value
    );

    let cantidad = parseInt(
        cantidadDetalle.value
    );

    let precio = parseFloat(
        precioDetalle.value
    );

    let subtotal = cantidad * precio;

    detalles.push({

        inventario_prenda_id: prenda.id,

        descripcion:
        prenda.tipo_prenda,

        talla:
        prenda.talla,

        cantidad:
        cantidad,

        precio_unitario:
        precio,

        subtotal:
        subtotal

    });

    tablaDetalles.innerHTML += `
        <tr class="border-b">
            <td>${prenda.tipo_prenda}</td>
            <td>${cantidad}</td>
            <td>${precio}</td>
            <td>${subtotal.toFixed(2)}</td>
        </tr>
    `;

    calcularSubtotal();

}


function calcularSubtotal(){

    let subtotal = 0;

    detalles.forEach(d => {

        subtotal += d.subtotal;

    });

    subtotalVista.textContent =
        subtotal.toFixed(2);

}





function guardarFactura(){

    let cliente =
        persona_id.options[
            persona_id.selectedIndex
        ].text;

    let subtotal = detalles.reduce(
        (acc,d) => acc + d.subtotal,
        0
    );

    let descuentoValor =
        parseFloat(descuento.value || 0);

    let total =
        subtotal - descuentoValor;

    const datos = {

        numero:
        numero.value,

        persona_id:
        persona_id.value,

        cliente_nombre:
        cliente,

        fecha:
        fecha.value,

        subtotal:
        subtotal,

        descuento:
        descuentoValor,

        impuesto:
        0,

        total:
        total,

        detalles:
        detalles

    };

    fetch('/api/facturas',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json'
        },

        body:
        JSON.stringify(datos)

    })
    .then(r => r.json())
    .then(data => {

        console.log(data);

        cerrarModal();

        cargarFacturas();

    });

}


function cargarFacturas(){

    fetch('/api/facturas')
    .then(r => r.json())
    .then(data => {

        tablaFacturas.innerHTML='';

        data.forEach(f => {

            tablaFacturas.innerHTML += `
                <tr class="border-b">
                    <td>${f.numero}</td>
                    <td>${f.cliente_nombre}</td>
                    <td>${f.fecha}</td>
                    <td>$${f.total}</td>
                    <td>${f.estado}</td>
                </tr>
            `;

        });

    });

}

cargarClientes();
cargarPrendas();
cargarFacturas();

</script>