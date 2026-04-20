<template>
  <div class="min-h-screen bg-gray-100 p-6">

    <h1 class="text-2xl font-bold mb-6">🤖 AI Product Insights</h1>

    <button
      @click="fetchAI"
      class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
    >
      Generate AI Report
    </button>

    <!-- Loading -->
    <div v-if="loading" class="mt-4 text-gray-500">
      AI is analyzing products...
    </div>

    <!-- AI Result -->
    <div
      v-if="result"
      class="mt-6 bg-white p-6 rounded-xl shadow whitespace-pre-line"
    >
      {{ result.slice(0, 113) }}
    </div>

    <!-- Empty -->
    <div v-if="!result && !loading" class="text-gray-400 mt-4">
      Click generate to get AI insights
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const result = ref('')
const loading = ref(false)

const fetchAI = async () => {
  loading.value = true
  result.value = ''

  try {
    const res = await axios.get('/api/products/slow')
   
    result.value = res.data.error.message
  } catch (e) {
    console.error(e)
    result.value = 'Error generating AI response'
  } finally {
    loading.value = false
  }
}
</script>