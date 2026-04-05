namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrasilApiService
{
    public function consultarCnpj($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}