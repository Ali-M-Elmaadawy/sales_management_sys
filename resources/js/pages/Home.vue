<template>
  <div>
    <h2 class="mb-4 text-success">Dashboard</h2>

    <div class="row">
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Users</h6>
          <h3>{{ stats.users }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Products</h6>
          <h3>{{ stats.products }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Orders</h6>
          <h3>{{ stats.orders }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Today Revenue</h6>
          <h3>{{ stats.today_total }}</h3>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Today Sold Products</h6>
          <h3>{{ stats.today_sold_products }}</h3>
        </div>
      </div>


      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h6>Revenue</h6>
          <h3>{{ stats.revenue }}</h3>
        </div>
      </div>

      <div class="col-md-6 mt-3">
        <div class="card p-3 text-center">
          <h6>Today's Orders</h6>
          <h3>{{ stats.today_orders }}</h3>
        </div>
      </div>

      <div class="col-md-6 mt-3">
        <div class="card p-3 text-center">
          <h6>Monthly Revenue</h6>
          <h3>{{ stats.monthly_revenue }}</h3>
        </div>
      </div>
    </div>

    <div v-if="loading" class="mt-3 text-muted">Loading...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref({
  users: 0,
  orders: 0,
  revenue: 0,
  today_orders: 0,
  monthly_revenue: 0
})

const loading = ref(false)

const getStats = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/dashboard/stats')
    stats.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(getStats)
</script>