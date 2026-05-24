<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovimientoMaterial;
use App\Models\InventarioMaterial;
use Illuminate\Http\Request;

class MovimientoMaterialController extends Controller
{
    public function index()
    {
        return MovimientoMaterial::with(
            'material'
        )->latest()->get();
    }


    public function store(Request $request)
    {

        $request->validate([

            'inventario_material_id'=>
            'required|exists:inventario_materiales,id',

            'tipo'=>
            'required|in:entrada,salida,ajuste',

            'cantidad'=>
            'required|numeric|min:0.01'

        ]);


        $material=
        InventarioMaterial::findOrFail(
            $request->inventario_material_id
        );


        $stockAnterior=
        $material->stock;


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

            $nuevoStock=
            $request->cantidad;

        }


        $material->update(['stock'=>$nuevoStock]);


        $movimiento = MovimientoMaterial::create([

    'inventario_material_id'=>
    $material->id,

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

}