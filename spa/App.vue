<template>
  <div class="container">
    <h1>Municípios por UF</h1>
    <form @submit.prevent="buscar">
      <label>
        UF:
        <input v-model="uf" maxlength="2" style="text-transform:uppercase" required />
      </label>
      <button type="submit">Buscar</button>
    </form>
    <div v-if="loading">Carregando...</div>
    <div v-if="erro" class="erro">{{ erro }}</div>
    <div v-if="municipios.length">
      <h2>Municípios de {{ uf.toUpperCase() }}</h2>
      <ul>
        <li v-for="m in municipios" :key="m.ibge_code">
          {{ m.name }} ({{ m.ibge_code }})
        </li>
      </ul>
      <div class="paginacao">
        <button @click="pagina--" :disabled="pagina === 1">Anterior</button>
        Página {{ pagina }}
        <button @click="pagina++" :disabled="municipios.length < porPagina">Próxima</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const uf = ref('SP')
const municipios = ref([])
const pagina = ref(1)
const porPagina = 15
const loading = ref(false)
const erro = ref('')

async function buscar() {
  loading.value = true
  erro.value = ''
  municipios.value = []
  try {
    const resp = await axios.get(`/api/ufs/${uf.value}/municipios`, {
      params: { page: pagina.value, per_page: porPagina }
    })
    municipios.value = resp.data.data
  } catch (e) {
    erro.value = e.response?.data?.title || 'Erro ao buscar municípios'
  } finally {
    loading.value = false
  }
}

watch(pagina, buscar)
</script>

<style scoped>
.container { max-width: 600px; margin: 2rem auto; font-family: sans-serif; }
form { margin-bottom: 1rem; }
.erro { color: red; margin: 1rem 0; }
.paginacao { margin-top: 1rem; }
</style>
