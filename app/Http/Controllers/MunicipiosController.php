<?php

namespace App\Http\Controllers;

use App\Services\Municipios\MunicipiosProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class MunicipiosController extends Controller
{
    public function index(Request $request, string $uf)
    {
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 15);
        $cacheKey = "municipios_{$uf}";
        $ttl = (int) env('MUNICIPIOS_CACHE_TTL', 3600);

        try {
            $municipios = Cache::remember($cacheKey, $ttl, function () use ($uf) {
                $provider = MunicipiosProviderService::make();
                return $provider->getMunicipiosByUf($uf);
            });
        } catch (\Throwable $e) {
            $municipios = Cache::get($cacheKey, []);
            if (empty($municipios)) {
                return response()->json([
                    'type' => 'https://tools.ietf.org/html/rfc7231#section-6.6.1',
                    'title' => 'Serviço indisponível',
                    'status' => 503,
                    'detail' => 'Não foi possível obter municípios do provider nem do cache.'
                ], 503);
            }
        }

        $total = count($municipios);
        $items = array_slice($municipios, ($page - 1) * $perPage, $perPage);
        $paginator = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return response()->json($paginator);
    }
}
