<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Services\BrasilApiService; 
use Illuminate\Http\Request;    

class EmpresaController extends Controller
{
    protected $brasilApiService;

    public function __construct(BrasilApiService $brasilApiService)
    {
        $this->brasilApiService = $brasilApiService;
    }

    public function consultarCnpj(Request $request)
    {
        $request->validate([
            'cnpj' => ['required', 'string'],
        ], [
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.string' => 'O CNPJ deve ser uma string válida.',
        ]);

        $cnpj = preg_replace('/\D/', '', $request->input('cnpj'));

        if (strlen($cnpj) !== 14) {
            return response()->json(['error' => 'CNPJ inválido'], 422);
        }

        $dadosEmpresa = $this->brasilApiService->consultarCnpj($cnpj);

        if ($dadosEmpresa) {
            // Salvar ou atualizar os dados da empresa no banco de dados
            $empresa = Empresa::updateOrCreate(
                ['cnpj' => $cnpj],
                [
                    'razao_social' => $dadosEmpresa['razao_social'] ?? null,
                    'nome_fantasia' => $dadosEmpresa['nome_fantasia'] ?? null,
                ]
            );

            return response()->json($empresa);
        }

        return response()->json(['error' => 'CNPJ não encontrado'], 404);
    }
}