<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedidaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // IMPORTANTE: Debe estar en true para que Laravel permita procesar el formulario
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la solicitud.
     */
    public function rules(): array
    {
        return [
            // El ID debe existir en la tabla 'personas'
            'persona_id'     => 'required|exists:personas,id',
            
            // Medidas obligatorias para el cálculo de tallas (Anexos A, B y C)
            'hombro'         => 'required|numeric|min:0',
            'cintura'        => 'required|numeric|min:0',
            
            // Medidas opcionales según el tipo de prenda (Anexo D y complementos)
            'cadera'         => 'nullable|numeric|min:0',
            'pecho'          => 'nullable|numeric|min:0',
            'largo_camisa'   => 'nullable|numeric|min:0',
            'largo_pantalon' => 'nullable|numeric|min:0',
            'largo_falda'    => 'nullable|numeric|min:0',
            'rodilla'        => 'nullable|numeric|min:0',
            'ruedo'          => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Personaliza los mensajes de error (Opcional, pero ayuda al usuario)
     */
    public function messages(): array
    {
        return [
            'persona_id.required' => 'Debes seleccionar a un alumno.',
            'persona_id.exists'   => 'El alumno seleccionado no es válido.',
            'hombro.required'     => 'La medida del hombro es obligatoria para calcular la talla de camisa.',
            'cintura.required'    => 'La medida de la cintura es necesaria para el pantalón o short.',
            'numeric'             => 'Este campo debe ser un número decimal.',
        ];
    }
}