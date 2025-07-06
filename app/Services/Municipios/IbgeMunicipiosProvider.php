<?php

namespace App\Services\Municipios;

use GuzzleHttp\Client;

class IbgeMunicipiosProvider implements ProviderMunicipiosInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/',
            'timeout'  => 5.0,
        ]);
    }

    public function getMunicipiosByUf(string $uf): array
    {
        $response = $this->client->get($uf . '/municipios');
        $data = json_decode($response->getBody(), true);
        return array_map(function ($item) {
            return [
                'name' => $item['nome'],
                'ibge_code' => $item['id'],
            ];
        }, $data);
    }
}
