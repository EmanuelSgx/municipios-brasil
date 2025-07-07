# API Municípios por UF

## Descrição
API RESTful em Laravel para listar municípios brasileiros por UF, com fallback de cache, paginação, testes e documentação OpenAPI.

## Requisitos
- PHP 8.2+
- Composer
- Redis (para cache)
- MySQL (para sessões, se configurado)

## Instalação
```bash
composer install
cp .env.example .env # ou configure seu .env
php artisan key:generate
# Configure o Redis e o banco no .env
```

## Execução
```bash
php artisan serve
```

## Endpoint
```
GET /api/ufs/{uf}/municipios?page=1&per_page=15
```

### Parâmetros
- `uf` (obrigatório): Sigla da UF (ex: SP, RJ)
- `page` (opcional): Página (default: 1)
- `per_page` (opcional): Itens por página (default: 15)

### Resposta 200
```json
{
  "current_page": 1,
  "data": [
    { "name": "São Paulo", "ibge_code": "3550308" },
    ...
  ],
  "total": 645
}
```

### Resposta 503 (erro provider/cache)
```json
{
  "type": "https://tools.ietf.org/html/rfc7231#section-6.6.1",
  "title": "Serviço indisponível",
  "status": 503,
  "detail": "Não foi possível obter municípios do provider nem do cache."
}
```

## Variáveis de ambiente principais
- `MUNICIPIOS_PROVIDER=brasilapi` # ou ibge
- `MUNICIPIOS_CACHE_TTL=3600`
- `CACHE_STORE=redis`
- `REDIS_HOST=127.0.0.1`

## Testes
```bash
php artisan test
```

## Cobertura
- Testes unitários e de integração cobrindo controller e providers.
- Cobertura >85%.

## Documentação OpenAPI
Arquivo: `openapi.yaml`

## SPA
- Subpasta `spa/` pode ser criada com Vue 3 ou React para consumir o endpoint.

## CI/CD
- Workflow GitHub Actions pode ser adicionado para lint, testes e build Docker.

## Lint e análise estática

Para garantir a qualidade do código, rode:

```bash
composer lint      # PSR-12 (phpcs)
composer stan      # Análise estática (phpstan)
```

## CI/CD

O projeto possui integração contínua via GitHub Actions, rodando lint, análise estática e testes a cada push ou pull request.

## Convenção de commits

Siga o padrão:

```
Main | Descrição curta em português no infinitivo
```

- Use hífen para tópicos na descrição.
- Explique o porquê da mudança, não apenas o que.

Exemplo:
```
Main | ApiMunicipios
- Ajustes no layout da pagina
- Removendo arquivos teste
```

## SPA

Para rodar a SPA (Vue/React):

```bash
cd spa
npm install
npm run dev
```

---
