<template>
  <div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card p-4 shadow" style="width: 400px;">
      <h3 class="text-center mb-4">Login</h3>

      <form @submit.prevent="login">
        <div class="mb-3">
          <label>Username</label>
          <input v-model="form.username" type="text" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input v-model="form.password" type="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100" :disabled="loading">
          {{ loading ? 'Loading...' : 'Login' }}
        </button>

        <div v-if="error" class="alert alert-danger mt-3">
          {{ error }}
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
  username: '',
  password: ''
})

const loading = ref(false)
const error = ref(null)

const login = async () => {
  loading.value = true
  error.value = null

  try {
    const res = await axios.post('/api/login', form)

    const token = res.data.token

    localStorage.setItem('token', token)

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    router.push('/home')
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>