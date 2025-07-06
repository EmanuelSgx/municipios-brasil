<?php

namespace App\Services\Municipios;

interface ProviderMunicipiosInterface
{
    /**
     * Busca municípios por UF.
     * @param string $uf
     * @return array [ [ 'name' => string, 'ibge_code' => string ], ... ]
     * @throws \Exception
     */
    public function getMunicipiosByUf(string $uf): array;
}
