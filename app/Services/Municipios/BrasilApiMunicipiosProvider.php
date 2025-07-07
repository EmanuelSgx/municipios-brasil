<?php

namespace App\Services\Municipios;

use GuzzleHttp\Client;

class BrasilApiMunicipiosProvider implements ProviderMunicipiosInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://brasilapi.com.br/api/ibge/municipios/v1/',
            'timeout'  => 5.0,
        ]);
    }

    /**
     * @param string $uf
     * @return array<int, array{name: string, ibge_code: string}>
     */
    public function getMunicipiosByUf(string $uf): array
    {
        $response = $this->client->get($uf);
        $data = json_decode($response->getBody()->getContents(), true);
        if (!is_array($data)) {
            return [];
        }
        return array_map(function (array $item): array {
            return [
                'name' => $item['nome'] ?? '',
                'ibge_code' => $item['codigo_ibge'] ?? '',
            ];
        }, $data);
    }
}
