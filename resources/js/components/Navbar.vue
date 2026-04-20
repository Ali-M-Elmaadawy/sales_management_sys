<template>
  <nav class="navbar navbar-light bg-light px-4 d-flex justify-content-between align-items-center">
    <span class="fw-bold">Admin Portal</span>

    <button @click="logout" class="btn btn-danger btn-sm">
      <i class="bi bi-box-arrow-right"></i> Logout
    </button>
  </nav>
</template>
<script>
import axios from 'axios'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const router = useRouter()

    const logout = async () => {
      try {
        await axios.post('/api/logout') 

        localStorage.removeItem('token')

        router.push('/login')
      } catch (error) {
        console.error(error)
      }
    }

    return { logout }
  }
}
</script>