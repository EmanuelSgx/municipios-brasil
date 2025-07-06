<?php

namespace Tests\Unit;

use App\Services\Municipios\BrasilApiMunicipiosProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BrasilApiMunicipiosProviderTest extends TestCase
{
    public function test_get_municipios_by_uf_retorna_array_padrao()
    {
        $mockResponse = new Response(200, [], json_encode([
            [ 'codigo_ibge' => 1, 'nome' => 'Cidade X' ],
            [ 'codigo_ibge' => 2, 'nome' => 'Cidade Y' ],
        ]));

        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn($mockResponse);

        $provider = new BrasilApiMunicipiosProvider();
        $providerReflection = new \ReflectionClass($provider);
        $clientProp = $providerReflection->getProperty('client');
        $clientProp->setAccessible(true);
        $clientProp->setValue($provider, $mockClient);

        $result = $provider->getMunicipiosByUf('SP');
        $this->assertEquals([
            [ 'name' => 'Cidade X', 'ibge_code' => 1 ],
            [ 'name' => 'Cidade Y', 'ibge_code' => 2 ],
        ], $result);
    }
}
