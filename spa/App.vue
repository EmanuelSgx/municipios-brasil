<template>
  <div class="container">
    <h1>🔎 Municípios por UF</h1>
    <form @submit.prevent="buscar" class="form-uf">
      <label>
        UF:
        <input v-model="uf" maxlength="2" style="text-transform:uppercase" required placeholder="Ex: SP" />
      </label>
      <button type="submit">Buscar</button>
    </form>
    <div v-if="loading" class="loading">
      <span class="spinner"></span> Carregando...
    </div>
    <div v-if="!loading && carregou && erro" class="erro">{{ erro }}</div>
    <div v-if="municipios.length">
      <h2>Municípios de <span class="uf-label">{{ uf.toUpperCase() }}</span></h2>
      <ul class="lista-municipios">
        <li v-for="m in municipios" :key="m.ibge_code">
          <span class="nome-mun">{{ m.name }}</span>
          <span class="ibge">({{ m.ibge_code }})</span>
        </li>
      </ul>
      <div class="paginacao">
        <button @click="irParaPagina(pagina - 1)" :disabled="pagina === 1">Anterior</button>
        <span v-for="p in paginasExibidas" :key="p">
          <button @click="irParaPagina(p)" :class="{ ativa: p === pagina }">{{ p }}</button>
        </span>
        <button @click="irParaPagina(pagina + 1)" :disabled="pagina === totalPaginas">Próxima</button>
      </div>
      <div class="info-total">
        Página <b>{{ pagina }}</b> de <b>{{ totalPaginas }}</b> | <b>{{ total }}</b> municípios
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import axios from 'axios'
import './App.css'

const uf = ref('SP')
const municipios = ref([])
const pagina = ref(1)
const porPagina = 15
const total = ref(0)
const totalPaginas = ref(1)
const loading = ref(false)

const erro = ref('')
const carregou = ref(false)

async function buscar() {
  loading.value = true
  erro.value = ''
  municipios.value = []
  carregou.value = false
  try {
    const resp = await axios.get(`/api/ufs/${uf.value}/municipios`, {
      params: { page: pagina.value, per_page: porPagina }
    })
    municipios.value = resp.data.data
    total.value = resp.data.total
    totalPaginas.value = Math.ceil(resp.data.total / porPagina)
    if (!municipios.value.length) {
      erro.value = `UF '${uf.value.toUpperCase()}' não encontrada ou sem municípios.`
    } else {
      erro.value = ''
    }
  } catch (e) {
    if (e.response && e.response.status === 503) {
      erro.value = 'Não houve uma resposta válida. Gentileza validar se a UF foi digitada corretamente.'
    } else {
      erro.value = 'Erro ao buscar municípios'
    }
  } finally {
    loading.value = false
    carregou.value = true
  }
}

function irParaPagina(p) {
  if (p >= 1 && p <= totalPaginas.value) {
    pagina.value = p
  }
}

const paginasExibidas = computed(() => {
  const max = 5
  let start = Math.max(1, pagina.value - 2)
  let end = Math.min(totalPaginas.value, start + max - 1)
  if (end - start < max - 1) {
    start = Math.max(1, end - max + 1)
  }
  const arr = []
  for (let i = start; i <= end; i++) arr.push(i)
  return arr
})

watch([pagina, uf], buscar, { immediate: true })
</script>
