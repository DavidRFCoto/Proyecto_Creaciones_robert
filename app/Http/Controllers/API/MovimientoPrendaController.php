<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovimientoPrenda;
use App\Models\InventarioPrenda;
use Illuminate\Http\Request;

class MovimientoPrendaController extends Controller
{
    public function index()
    {
        return MovimientoPrenda::with(
            'prenda'
        )
        ->latest()
        ->get();
    }


    public function store(Request $request)
    {
        $request->validate([

            'inventario_prenda_id'=>
            'required|exists:inventario_prendas,id',

            'tipo'=>
            'required|in:entrada,salida,ajuste',

            'cantidad'=>
            'required|integer|min:1'

        ]);


        $prenda=
        InventarioPrenda::findOrFail(
            $request->inventario_prenda_id
        );


        $stockAnterior=
        $prenda->stock;


        if(
            $request->tipo=='entrada'
        ){

            $nuevoStock=
            $stockAnterior
            +
            $request->cantidad;

        }

        elseif(
            $request->tipo=='salida'
        ){

            $nuevoStock=
            $stockAnterior
            -
            $request->cantidad;

            if($nuevoStock<0){

                return response()->json([

                    'success'=>false,

                    'mensaje'=>
                    'Stock insuficiente'

                ],422);

            }

        }

        else{

            // ajuste manual
            $nuevoStock=
            $request->cantidad;

        }


        $prenda->update([

            'stock'=>$nuevoStock

        ]);


        $movimiento=
        MovimientoPrenda::create([

            'inventario_prenda_id'=>
            $prenda->id,

            'user_id'=>
            auth()->id(),

            'tipo'=>
            $request->tipo,

            'cantidad'=>
            $request->cantidad,

            'stock_anterior'=>
            $stockAnterior,

            'stock_nuevo'=>
            $nuevoStock,

            'motivo'=>
            $request->motivo,

            'referencia'=>
            $request->referencia,

            'descripcion'=>
            $request->descripcion

        ]);


        return response()->json([

            'success'=>true,

            'movimiento'=>$movimiento

        ]);

    }


    public function destroy($id)
    {
        MovimientoPrenda::findOrFail($id)
        ->delete();

        return response()->json([

            'success'=>true

        ]);
    }


    public function show(string $id){}
}