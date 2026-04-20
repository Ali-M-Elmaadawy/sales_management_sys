<template>
  <div>

    <!-- 🔘 Open Modal -->
    <button class="btn btn-primary mb-3" @click="openModal">
      + Add Order
    </button>
    <div class="mb-3">
      <input
        v-model="search"
        @keyup.enter="getOrders()"
        type="text"
        class="form-control"
        placeholder="Search by name"
      />
    </div>
    <!-- 📋 Orders Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Sub-Total</th>
          <th>Tax</th>
          <th>Total</th>
          <th>Created At</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="o in orders" :key="o.id">
          <td>#{{ o.id }}</td>
          <td>{{ o.user?.name }}</td>
          <td>{{ o.subtotal }}</td>
          <td>{{ o.tax }}</td>
          <td>{{ o.total }}</td>
          <td>{{ o.created_at_human }}</td>
          <td>
            <button class="btn btn-sm btn-info" @click="openDetails(o)">
              Show Details
            </button>
            |
            <button class="btn btn-sm btn-danger" @click="confirmDelete(o)">
              Delete
            </button>
            |
            <button class="btn btn-sm btn-primary" @click="confirmRestore(o)">
              Restore
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="showModal" class="modal fade show d-block">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h5>Add Order</h5>
            <button class="btn-close" @click="closeModal"></button>
          </div>

          <div class="modal-body">

            <!-- 👤 User -->
            <label>Select User</label>
            <select v-model="form.user_id" @change="resetItems" class="form-control mb-3">
              <option v-for="u in users" :key="u.id" :value="u.id">
                {{ u.name }}
              </option>
            </select>

            <!-- 📦 Items -->
            <div v-for="(item, index) in form.items" :key="index" class="card p-3 mb-2">

              <div class="row">

                <!-- product -->
                <div class="col-md-5">
                  <label>Product</label>
                  <select v-model="item.product_id" @change="onProductChange(item)" class="form-control">
                    <option
                      v-for="p in products"
                      :key="p.id"
                      :value="p.id"
                      :disabled="p.qty === 0 || isUsed(p.id, index)"
                    >
                      {{ p.name }} ({{ p.qty }} left)
                    </option>
                  </select>
                </div>

                <!-- qty -->
                <div class="col-md-3">
                  <label>Qty</label>
                  <input type="number" v-model.number="item.qty" @input="recalc" class="form-control">
                </div>

                <!-- total -->
                <div class="col-md-2">
                  <label>Total</label>
                  <input type="text" :value="item.total || 0" class="form-control" readonly>
                </div>

                <!-- delete -->
                <div class="col-md-2 d-flex align-items-end">
                  <button class="btn btn-danger btn-sm" @click="removeItem(index)">
                    X
                  </button>
                </div>

              </div>

              <small class="text-danger" v-if="item.error">{{ item.error }}</small>

            </div>

            <button class="btn btn-secondary mb-3" @click="addItem">
              + Add Product
            </button>

            <hr>

            <h5>Subtotal: {{ subtotal }}</h5>
            <h5>Tax: {{ tax }}</h5>
            <h4>Total: {{ total }}</h4>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Close</button>
            <button class="btn btn-success" @click="saveOrder">
              Save Order
            </button>
          </div>

        </div>
      </div>
    </div>

    <div v-if="showDetailsModal" class="modal fade show d-block">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h5>Order Details #{{ selectedOrder?.id }}</h5>
            <button class="btn-close" @click="closeDetails"></button>
          </div>

          <div class="modal-body">

            <p><strong>User:</strong> {{ selectedOrder?.user?.name }}</p>
            <p><strong>Total:</strong> {{ selectedOrder?.total }}</p>
            <p><strong>Tax:</strong> {{ selectedOrder?.tax }}</p>

            <hr>

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Tax</th>
                  <th>Total</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in selectedOrder?.items" :key="item.id">
                  <td>{{ item.product?.name }}</td>
                  <td>{{ item.qty }}</td>
                  <td>{{ item.price }}</td>
                  <td>{{ item.tax }}</td>
                  <td>{{ (parseFloat(item.price) + parseFloat(item.tax)).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>

          </div>

        </div>
      </div>
    </div>



  </div>

  <div class="d-flex justify-content-center mt-3">
    <button 
      class="btn btn-sm btn-secondary me-2"
      :disabled="!pagination.prev_page_url"
      @click="getOrders(pagination.current_page - 1)"
    >
      Prev
    </button>

    <span class="align-self-center">
      Page {{ pagination.current_page }} of {{ pagination.last_page }}
    </span>

    <button 
      class="btn btn-sm btn-secondary ms-2"
      :disabled="!pagination.next_page_url"
      @click="getOrders(pagination.current_page + 1)"
    >
      Next
    </button>
  </div>

</template>

<script setup>
import { ref, reactive, onMounted , computed } from 'vue'
import { useRoute } from 'vue-router'

import axios from 'axios'
import Swal from 'sweetalert2'
/* ===================== STATE ===================== */
const users = ref([])
const route = useRoute()
const products = ref([])
const orders = ref([])
const showModal = ref(false)
const showDetailsModal = ref(false)
const selectedOrder = ref(null)
const subtotal = ref(0)
const tax = ref(0)
const total = ref(0)
const search = ref(route.query.search || '')
const pagination = ref({})
/* ===================== FORM ===================== */
const form = reactive({
  user_id: null,
  items: []
})

/* ===================== API ===================== */
const getUsers = async () => {
  const res = await axios.get('/api/get_all_users')
  users.value = res.data
}

const getProducts = async () => {
  const res = await axios.get('/api/get_all_products')
  products.value = res.data
}


const getOrders = async (page = 1) => {
  const res = await axios.get(`/api/orders?page=${page}&search=${search.value}`)
  orders.value = res.data.data
  pagination.value = res.data
}


const filteredOrders = computed(() => {
  return orders.value.filter(order => {
    const term = search.value.toLowerCase()

    return (
      order.user.name.toLowerCase().includes(term))
  })
})

/* ===================== MODAL ===================== */
const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetAll()
}
const openDetails = (order) => {
  selectedOrder.value = order
  showDetailsModal.value = true
}

const closeDetails = () => {
  showDetailsModal.value = false
  selectedOrder.value = null
}
/* ===================== ITEMS ===================== */
const addItem = () => {
  form.items.push({
    product_id: null,
    qty: 1,
    price: 0,
    total: 0,
    error: null
  })
}

const removeItem = (index) => {
  form.items.splice(index, 1)
  recalc()
}

const resetItems = () => {
  form.items = []
  subtotal.value = 0
  tax.value = 0
  total.value = 0
}

/* ===================== LOGIC ===================== */
const onProductChange = (item) => {
  const product = products.value.find(p => p.id == item.product_id)
  if (!product) return

  item.price = Number(product.price)
  item.qty = 1
  recalc()
}

const recalc = () => {
  let sub = 0
  let tx = 0

  form.items.forEach(item => {

    const product = products.value.find(p => p.id == item.product_id)
    if (!product) return

    item.qty = Number(item.qty)

    if (!item.qty || item.qty <= 0) {
      item.qty = 1
    }

    if (item.qty > product.qty) {
      item.qty = product.qty
    }

    const qty = item.qty
    const price = Number(product.price)

    const lineTotal = price * qty

    item.total = lineTotal

    sub += lineTotal

    if (product.tax_type === 'percent') {
      tx += (lineTotal * (Number(product.tax) || 0)) / 100
    } else {
      tx += (Number(product.tax) || 0) * qty
    }
  })

  subtotal.value = sub
  tax.value = tx
  total.value = sub + tx
}

const isUsed = (productId, index) => {
  return form.items.some((item, i) =>
    item.product_id == productId && i !== index
  )
}

/* ===================== SAVE ORDER ===================== */
const saveOrder = async () => {
  try {
    await axios.post('/api/orders', {
      user_id: form.user_id,
      items: form.items
    })

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: `Order Created successfully`,
      showConfirmButton: false,
      timer: 2000
    })

    closeModal()
    getOrders()

  } catch (e) {
      if (e.response && e.response.status === 422) {

        const validationErrors = e.response.data.errors

        let message = Object.values(validationErrors)
          .flat()
          .join('<br>')

        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          html: message,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        })

      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: 'Something went wrong',
          showConfirmButton: false,
          timer: 3000
        })
      }
  }
}

/* ===================== RESET ===================== */
const resetAll = () => {
  form.user_id = null
  form.items = []

  subtotal.value = 0
  tax.value = 0
  total.value = 0
}

const confirmDelete = async (order) => {
  Swal.fire({
    title: 'Delete Order?',
    text: 'Do you want to Delete Order?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  }).then(async (result) => {

    if (result.isConfirmed) {
      var data = await axios.delete(`/api/orders/${order.id}`)

      getOrders()

      Swal.fire('Deleted!', `${data.data.message}`, 'success')
    }

  })
}

const confirmRestore = async (order) => {
  Swal.fire({
    title: 'Restore Products?',
    text: 'Do you want to Delete Order And Restore Products Stocks?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  }).then(async (result) => {

    if (result.isConfirmed) {
      var data = await axios.post(`/api/orders/restore/${order.id}`)
  
      getOrders()

      Swal.fire('Deleted!', `${data.data.message}`, 'success')
    }

  })
}

/* ===================== INIT ===================== */
onMounted(() => {
  getUsers()
  getProducts()
  getOrders()
})
</script>