<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedidaRequest; 
use App\Models\Persona;
use App\Models\Medida;
use App\Services\CalculadoraTallasService;

class MedidaController extends Controller
{
    protected $calculadora;

    public function __construct(CalculadoraTallasService $calculadora)
    {
        $this->calculadora = $calculadora;
    }

    // LISTAR todas las medidas con los datos de la persona
    public function index()
    {
        $medidas = Medida::with('persona')->orderBy('created_at', 'desc')->get();
        return response()->json($medidas);
    }

    public function store(StoreMedidaRequest $request)
    {
        $persona = Persona::find($request->persona_id);
        if (!$persona) return response()->json(['error' => 'Persona no encontrada'], 404);

        $sexo = strtolower($persona->sexo);
        
        $resCamisa   = $this->calculadora->calcularTallaCamisa($request->hombro);
        $resInferior = ['talla' => 'N/A', 'sug' => ''];
        $tipoPrenda  = "N/A";

        if ($sexo === 'masculino') {
            if ($request->cintura <= 64) {
                $resInferior = $this->calculadora->calcularTallaShort($request->cintura);
                $tipoPrenda = "Short";
            } else {
                $resInferior = $this->calculadora->calcularTallaPantalon($request->cintura);
                $tipoPrenda = "Pantalón";
            }
        } else if ($sexo === 'femenino') {
            $resInferior = $this->calculadora->calcularTallaFalda($request->cadera);
            $tipoPrenda = "Falda";
        }

        $perfil = "Camisa: {$resCamisa['talla']} ({$resCamisa['sug']}) | $tipoPrenda: {$resInferior['talla']} ({$resInferior['sug']})";

        $nuevaMedida = Medida::create([
            'persona_id'     => $request->persona_id,
            'largo_camisa'   => $request->largo_camisa ?? 0,
            'hombro'         => $request->hombro,
            'pecho'          => $request->pecho ?? 0,
            'largo_pantalon' => $request->largo_pantalon ?? 0,
            'cintura'        => $request->cintura,
            'cadera'         => $request->cadera ?? 0,
            'rodilla'        => $request->rodilla ?? 0,
            'ruedo'          => $request->ruedo ?? 0,
            'largo_falda'    => $request->largo_falda ?? 0,
            'talla_sugerida' => $perfil
        ]);

        return response()->json([
            'success' => true, 
            'perfil'  => $perfil,
            'alumno'  => $persona->nombre
        ]);
    }

    // ELIMINAR una medida
    public function destroy($id)
    {
        $medida = Medida::find($id);
        if (!$medida) return response()->json(['success' => false], 404);
        
        $medida->delete();
        return response()->json(['success' => true]);
    }
}