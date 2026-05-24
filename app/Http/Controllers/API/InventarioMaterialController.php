<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InventarioMaterial;
use Illuminate\Http\Request;


class InventarioMaterialController extends Controller
{
    public function index()
{
    return InventarioMaterial::all();
}

    public function store(Request $request)
    {
        $request->validate([

        'nombre'=>'required|max:255',

        'categoria'=>'required',

        'unidad_medida'=>'required',

        'stock'=>'required|numeric|min:0',

        'stock_minimo'=>'required|numeric|min:0',

        'precio_compra'=>'nullable|numeric',

        'precio_venta'=>'nullable|numeric',

        'fecha_ingreso'=>'nullable|date'

]);


         $material = InventarioMaterial::create([
               'nombre' => $request->nombre,

    'categoria' => $request->categoria,

    'color' => $request->color,

    'marca' => $request->marca,

    'stock' => $request->stock,

    'unidad_medida' => $request->unidad_medida,

    'stock_minimo' => $request->stock_minimo,

    'precio_compra' => $request->precio_compra,

    'precio_venta' => $request->precio_venta,

    'proveedor' => $request->proveedor,

    'lote' => $request->lote,

    'fecha_ingreso' => $request->fecha_ingreso,

    'descripcion' => $request->descripcion
        ]);

        return response()->json([

            'success'=>true,
            'material'=>$material

        ]);
    }


    public function destroy($id)
    {
        InventarioMaterial::findOrFail($id)
            ->delete();

        return response()->json([

            'success'=>true

        ]);
    }
    public function show(string $id){}
   public function update(
Request $request,
string $id
){

$material=
InventarioMaterial::findOrFail($id);

$material->update([

'nombre'=>$request->nombre,

'categoria'=>$request->categoria,

'color'=>$request->color,

'marca'=>$request->marca,

'stock'=>$request->stock,

'unidad_medida'=>$request->unidad_medida,

'stock_minimo'=>$request->stock_minimo,

'precio_compra'=>$request->precio_compra,

'precio_venta'=>$request->precio_venta,

'proveedor'=>$request->proveedor,

'lote'=>$request->lote,

'fecha_ingreso'=>$request->fecha_ingreso,

'descripcion'=>$request->descripcion

]);

return response()->json([

'success'=>true,

'material'=>$material

]);

}


    
}