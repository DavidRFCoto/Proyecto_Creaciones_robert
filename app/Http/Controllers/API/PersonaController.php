<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        return response()->json(Persona::orderBy('nombre', 'asc')->get());
    }

    public function store(Request $request)
    {
        // El campo grupo_grado ahora es totalmente opcional
        $request->validate([
            'nombre' => 'required|string|max:255',
            'sexo' => 'required|in:Masculino,Femenino,masculino,femenino',
            'tipo' => 'required|in:alumno,cliente,empresa',
            'nombre_colegio'=>'nullable|string|max:255',
            'grupo_grado' => 'nullable|string|max:50',
            'nombre_empresa' => 'nullable|string|max:255' 
        ]);

        $persona = Persona::create([
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'tipo' => $request->tipo, 
            'nombre_empresa' => $request->nombre_empresa,
            // Si el campo viene vacío, le ponemos 'N/A' por defecto (Así el listado sabe que es Cliente)
            'nombre_colegio'=>$request->nombre_colegio,
            'grupo_grado' => $request->grupo_grado ?: 'N/A'
        ]);

        return response()->json([
            'success' => true,
            'persona' => $persona
        ]);
    }
}