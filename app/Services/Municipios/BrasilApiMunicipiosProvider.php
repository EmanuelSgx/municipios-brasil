<?php

namespace App\Services\Municipios;

use GuzzleHttp\Client;

class BrasilApiMunicipiosProvider implements ProviderMunicipiosInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://brasilapi.com.br/api/ibge/municipios/v1/',
            'timeout'  => 5.0,
        ]);
    }

    public function getMunicipiosByUf(string $uf): array
    {
        $response = $this->client->get($uf);
        $data = json_decode($response->getBody(), true);
        return array_map(function ($item) {
            return [
                'name' => $item['nome'],
                'ibge_code' => $item['codigo_ibge'],
            ];
        }, $data);
    }
}
