<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InventarioPrenda;
use Illuminate\Http\Request;

class InventarioPrendaController extends Controller
{
    public function index()
    {
        return InventarioPrenda::all();
    }

    public function store(Request $request)
    {
        $request->validate([

            'nombre'=>'required|max:255',

            'tipo_prenda'=>'required',

            'talla'=>'required',

            'stock'=>'required|numeric|min:0',

            'costo_produccion'=>'nullable|numeric',

            'precio_venta'=>'nullable|numeric'

        ]);


        $prenda=InventarioPrenda::create([

            'nombre'=>$request->nombre,

            'tipo_prenda'=>$request->tipo_prenda,

            'categoria'=>$request->categoria,

            'talla'=>$request->talla,

            'sexo'=>$request->sexo,

            'color'=>$request->color,

            'stock'=>$request->stock,

            'costo_produccion'=>$request->costo_produccion,

            'precio_venta'=>$request->precio_venta,

            'motivo'=>$request->motivo,

            'descripcion'=>$request->descripcion

        ]);

        return response()->json([

            'success'=>true,

            'prenda'=>$prenda

        ]);
    }


    public function update(
        Request $request,
        InventarioPrenda $inventarioPrenda
    ){

        $inventarioPrenda->update([

            'nombre'=>$request->nombre,

            'tipo_prenda'=>$request->tipo_prenda,

            'categoria'=>$request->categoria,

            'talla'=>$request->talla,

            'sexo'=>$request->sexo,

            'color'=>$request->color,

            'stock'=>$request->stock,

            'costo_produccion'=>$request->costo_produccion,

            'precio_venta'=>$request->precio_venta,

            'motivo'=>$request->motivo,

            'descripcion'=>$request->descripcion

        ]);

        return response()->json([

            'success'=>true,

            'mensaje'=>'Prenda actualizada'

        ]);
    }


    public function destroy($id)
    {
        InventarioPrenda::findOrFail($id)
        ->delete();

        return response()->json([

            'success'=>true

        ]);
    }

    public function show(string $id){}
}