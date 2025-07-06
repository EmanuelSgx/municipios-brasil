<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MunicipiosControllerTest extends TestCase
{
    public function test_retorna_municipios_com_sucesso()
    {
        Cache::shouldReceive('remember')
            ->andReturn([
                ['name' => 'Cidade Teste', 'ibge_code' => '1234567'],
                ['name' => 'Cidade Dois', 'ibge_code' => '7654321'],
            ]);

        $response = $this->getJson('/api/ufs/SP/municipios');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Cidade Teste', 'ibge_code' => '1234567']);
    }

    public function test_fallback_para_cache_quando_provider_falha()
    {
        Cache::shouldReceive('remember')->andThrow(new \Exception('Erro provider'));
        Cache::shouldReceive('get')->andReturn([
            ['name' => 'Cidade Cache', 'ibge_code' => '9999999'],
        ]);

        $response = $this->getJson('/api/ufs/SP/municipios');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Cidade Cache', 'ibge_code' => '9999999']);
    }

    public function test_retorna_erro_503_quando_sem_cache_e_provider()
    {
        Cache::shouldReceive('remember')->andThrow(new \Exception('Erro provider'));
        Cache::shouldReceive('get')->andReturn([]);

        $response = $this->getJson('/api/ufs/SP/municipios');
        $response->assertStatus(503)
            ->assertJsonFragment(['status' => 503]);
    }
}
