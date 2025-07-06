<?php

namespace Tests\Unit;

use App\Services\Municipios\IbgeMunicipiosProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class IbgeMunicipiosProviderTest extends TestCase
{
    public function test_get_municipios_by_uf_retorna_array_padrao()
    {
        $mockResponse = new Response(200, [], json_encode([
            [ 'id' => 1, 'nome' => 'Cidade A' ],
            [ 'id' => 2, 'nome' => 'Cidade B' ],
        ]));

        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn($mockResponse);

        $provider = new IbgeMunicipiosProvider();
        $providerReflection = new \ReflectionClass($provider);
        $clientProp = $providerReflection->getProperty('client');
        $clientProp->setAccessible(true);
        $clientProp->setValue($provider, $mockClient);

        $result = $provider->getMunicipiosByUf('SP');
        $this->assertEquals([
            [ 'name' => 'Cidade A', 'ibge_code' => 1 ],
            [ 'name' => 'Cidade B', 'ibge_code' => 2 ],
        ], $result);
    }
}
