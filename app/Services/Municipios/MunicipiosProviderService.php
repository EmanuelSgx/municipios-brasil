<?php

namespace App\Services\Municipios;

use Illuminate\Support\Facades\App;

class MunicipiosProviderService
{
    /**
     * @return ProviderMunicipiosInterface
     */
    public static function make(): ProviderMunicipiosInterface
    {
        $provider = env('MUNICIPIOS_PROVIDER', 'brasilapi');
        return match ($provider) {
            'ibge' => App::make(IbgeMunicipiosProvider::class),
            default => App::make(BrasilApiMunicipiosProvider::class),
        } instanceof ProviderMunicipiosInterface
            ? App::make($provider === 'ibge' ? IbgeMunicipiosProvider::class : BrasilApiMunicipiosProvider::class)
            : throw new \RuntimeException('Provider inválido');
    }
}
