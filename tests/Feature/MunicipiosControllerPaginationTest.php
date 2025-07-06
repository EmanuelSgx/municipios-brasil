<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MunicipiosControllerPaginationTest extends TestCase
{
    public function test_paginacao_funciona_corretamente()
    {
        $dados = [];
        for ($i = 1; $i <= 30; $i++) {
            $dados[] = ['name' => 'Cidade '.$i, 'ibge_code' => (string)$i];
        }
        Cache::shouldReceive('remember')->andReturn($dados);

        $response = $this->getJson('/api/ufs/SP/municipios?page=2&per_page=10');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Cidade 11', 'ibge_code' => '11'])
            ->assertJsonFragment(['name' => 'Cidade 20', 'ibge_code' => '20']);
    }
}
