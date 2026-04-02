<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona; // Importante para interactuar con la tabla personas
use App\Models\Medida;  // Importante para futuras relaciones de tallas

class PersonaController extends Controller
{
    /**
     * Muestra una lista de todas las personas registradas.
     * Ideal para ver el listado de una escuela o empresa.
     */
    public function index()
    {
        // Traemos a las personas con sus medidas cargadas (Eager Loading)
        $personas = Persona::with('medidas')->get();
        return response()->json($personas, 200);
    }

    /**
     * Registra una nueva persona (Alumno, Empleado o Cliente).
     */
    public function store(Request $request)
    {
        // Validamos que los datos cumplan con los requisitos de SIGETEX
        $validatedData = $request->validate([
            'nombre'      => 'required|string|max:255',
            'sexo'        => 'required|in:Masculino,Femenino',
            'grupo_grado' => 'required|string|max:100', // Ejemplo: "9no A" o "Ventas"
        ]);

        // Creamos el registro usando el Modelo
        $persona = Persona::create($validatedData);

        return response()->json([
            'message' => 'Persona registrada con éxito en el sistema',
            'data'    => $persona
        ], 201);
    }

    /**
     * Muestra los detalles de una persona específica y sus medidas.
     */
    public function show(string $id)
    {
        $persona = Persona::with('medidas')->find($id);

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        return response()->json($persona, 200);
    }

    /**
     * Actualiza los datos de una persona (ejemplo: cambio de grado o error en nombre).
     */
    public function update(Request $request, string $id)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $validatedData = $request->validate([
            'nombre'      => 'string|max:255',
            'sexo'        => 'in:Masculino,Femenino',
            'grupo_grado' => 'string|max:100',
        ]);

        $persona->update($validatedData);

        return response()->json([
            'message' => 'Datos actualizados correctamente',
            'data'    => $persona
        ], 200);
    }

    /**
     * Elimina un registro del sistema.
     */
    public function destroy(string $id)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $persona->delete();

        return response()->json(['message' => 'Registro eliminado de SIGETEX'], 200);
    }
}