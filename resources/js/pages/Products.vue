<template>
  <div>
    <div class="d-flex justify-content-between mb-3">
      <h3>Products</h3>
      
      <button class="btn btn-primary" @click="openModal()">Add Product</button>
    </div>
    <div class="mb-3">
    <input
      v-model="search"
      @keyup.enter="getProducts()"
      type="text"
      class="form-control"
      placeholder="Search by name"
    />
    </div>


    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Tax Type</th>
          <th>Tax</th>
          <th>Sale Count</th>
          <th>Last Sale Date</th>
          <th width="150">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="p in products" :key="p.id" :class="{ 'table-danger': p.qty == 0 }">
        <td>{{ p.id }}</td>
        <td>
          <img :src="imageUrl(p.image)" width="60" v-if="p.image">
        </td>

        <td>{{ p.name }}</td>
        <td>{{ p.price }}</td>
        <td>{{ p.qty == 0 ? 'outOfStock' : p.qty }}</td>
        <td>{{ p.tax_type }}</td>
        <td>{{ p.tax }}</td>
        <td>{{ p.sale_count }}</td>
        <td>{{ p.last_sale_date }}</td>


        <td>
          <button class="btn btn-sm btn-primary me-2" @click="openModal(p)">Edit</button>
          <button class="btn btn-sm btn-danger" @click="del(p.id)">Delete</button>
        </td>
        </tr>
      </tbody>
    </table>



    <!-- Modal -->
    <div v-if="showModal" class="modal fade show d-block">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">
              {{ form.id ? 'Edit Product' : 'Add Product' }}
            </h5>
            <button class="btn-close" @click="closeModal"></button>
          </div>

          <div class="modal-body">
            <label>Name</label>
            <input v-model="form.name" placeholder="Name" class="form-control mb-2">
            <label>Price</label>
            <input v-model="form.price" placeholder="Price" class="form-control mb-2">
            <label>Qty</label>
            <input v-model="form.qty" placeholder="Qty" class="form-control mb-2">
            <label>Tax Type</label>
            <select v-model="form.tax_type" class="form-control mb-2">
                <option value="percent">Percentage (%)</option>
                <option value="fixed">Fixed Amount</option>
            </select>    
            <label>Tax</label>
            <input v-model="form.tax" placeholder="Tax" class="form-control mb-2">
            <label>Description</label>
            <textarea v-model="form.description" placeholder="Description" class="form-control mb-2"></textarea>

            <input type="file" @change="handleFile" class="form-control">

            <!-- preview -->
            <img v-if="preview" :src="preview" class="mt-2" width="100">
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Close</button>
            <button class="btn btn-success" @click="saveProduct">
              {{ form.id ? 'Update' : 'Save' }}
            </button>
          </div>

        </div>
      </div>
    </div>


  </div>
  <div class="d-flex justify-content-center mt-3">
    <button 
      class="btn btn-sm btn-secondary me-2"
      :disabled="!pagination.prev_page_url"
      @click="getProducts(pagination.current_page - 1)"
    >
      Prev
    </button>

    <span class="align-self-center">
      Page {{ pagination.current_page }} of {{ pagination.last_page }}
    </span>

    <button 
      class="btn btn-sm btn-secondary ms-2"
      :disabled="!pagination.next_page_url"
      @click="getProducts(pagination.current_page + 1)"
    >
      Next
    </button>
  </div>


</template>

<script setup>
import { ref, reactive, onMounted , computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
const products = ref([])
const showModal = ref(false)
const preview = ref(null)
const search = ref('')
const pagination = ref({})


const form = reactive({
  id: null,
  name: '',
  price: '',
  tax_type: '',
  qty: '',
  price: '',
  description: '',
  image: null
})

const getProducts = async (page = 1) => {
  const res = await axios.get(`/api/products?page=${page}&search=${search.value}`)
  products.value = res.data.data
  pagination.value = res.data
}


const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const term = search.value.toLowerCase()

    return (
      product.name.toLowerCase().includes(term))
  })
})
const openModal = (product = null) => {
  showModal.value = true

  if (product) {
    Object.assign(form, product)
    preview.value = imageUrl(product.image)
  } else {
    reset()
  }
}

const closeModal = () => {
  showModal.value = false
  reset()
}

const handleFile = (e) => {
  const file = e.target.files[0]
  form.image = file
  preview.value = URL.createObjectURL(file)
}

const saveProduct = async () => {
  try {
      let data = new FormData()

      data.append('name', form.name)
      data.append('price', form.price)
      data.append('qty', form.qty)
      data.append('tax_type', form.tax_type)
      data.append('tax', form.tax)
      data.append('description', form.description)

      if (form.image) {
        data.append('image', form.image)
      }

      if (form.id) {
        var status = 'updated';
        await axios.post(`/api/products/${form.id}?_method=PUT`, data)
      } else {
        var status = 'added';
        await axios.post('/api/products', data)
      }
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `Product ${status} successfully`,
        showConfirmButton: false,
        timer: 2000
      })
      closeModal()
      getProducts()
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

const del = async (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'Do you want to Delete This Record?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  }).then(async (result) => {

    if (result.isConfirmed) {
      var data = await axios.delete(`/api/products/${id}`)


      if(data.data.status == false) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: `${data.data.msg}`,
          showConfirmButton: false,
          timer: 2000
        })
        return;
      }
      getProducts()
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `Record deleted successfully`,
        showConfirmButton: false,
        timer: 2000
      })
    }
  })
}

const reset = () => {
  form.id = null
  form.name = ''
  form.price = ''
  form.description = ''
  form.qty = ''
  form.tax = ''
  form.image = null
  preview.value = null
}

const imageUrl = (img) => {
  return `http://127.0.0.1:8000/storage/${img}`
}

onMounted(getProducts)
</script>