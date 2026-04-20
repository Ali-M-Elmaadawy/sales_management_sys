<template>
  <div>
    <h3 class="mb-3">Users</h3>
<div class="mb-3">
  <input
    v-model="search"
    @keyup.enter="getUsers()"
    type="text"
    class="form-control"
    placeholder="Search by name or phone..."
  />
</div>
    <!-- Form -->
    <div class="card p-3 mb-4">
      <form @submit.prevent="saveUser">
        <div class="row">
          <div class="col-md-3">
          <label>name</label>
            <input v-model="form.name" class="form-control" placeholder="Name">
          </div>

          <div class="col-md-3">
          <label>email</label>
            <input v-model="form.email" class="form-control" placeholder="Email">
          </div>

          <div class="col-md-2">
          <label>phone</label>
            <input v-model="form.phone" class="form-control" placeholder="Phone">
          </div>

          <div class="col-md-3">
          <label>address</label>
            <input v-model="form.address" class="form-control" placeholder="Address">
          </div>

          <div class="col-md-1">
            <button class="btn btn-success w-100">
              {{ form.id ? 'Update' : 'Add' }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Orders NUM</th>
          <th>Products NUM</th>
          <th>Last Order Date</th>
          <th width="150">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone }}</td>
          <td>{{ user.address }}</td>
          <td>{{ user.sale_count }}</td>
          <td>{{ user.sale_products_count }}</td>
          <td>{{ user.last_sale_date }}</td>

          <td>
            <button class="btn btn-sm btn-primary me-2" @click="editUser(user)">
              Edit
            </button>
            <button class="btn btn-sm btn-danger" @click="deleteUser(user.id)">
              Delete
            </button>
          <button 
            class="btn btn-sm btn-info me-2"
            @click="$router.push({ path: '/sales', query: { search: user.id } })">
            Orders
          </button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>

<div class="d-flex justify-content-center mt-3">
  <button 
    class="btn btn-sm btn-secondary me-2"
    :disabled="!pagination.prev_page_url"
    @click="getUsers(pagination.current_page - 1)"
  >
    Prev
  </button>

  <span class="align-self-center">
    Page {{ pagination.current_page }} of {{ pagination.last_page }}
  </span>

  <button 
    class="btn btn-sm btn-secondary ms-2"
    :disabled="!pagination.next_page_url"
    @click="getUsers(pagination.current_page + 1)"
  >
    Next
  </button>
</div>

</template>

<script setup>
import { ref, reactive, onMounted , computed  } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
const users = ref([])
const errors = ref({})
const search = ref('')
const pagination = ref({})
const filteredUsers = computed(() => {
  return users.value.filter(user => {
    const term = search.value.toLowerCase()

    return (
      user.name.toLowerCase().includes(term) ||
      user.phone.toLowerCase().includes(term)
    )
  })
})
const form = reactive({
  id: null,
  name: '',
  email: '',
  phone: '',
  address: ''
})

const getUsers = async (page = 1) => {
  const res = await axios.get(`/api/users?page=${page}&search=${search.value}`)
  users.value = res.data.data
  pagination.value = res.data
}

const saveUser = async () => {
  try {
    if (form.id) {
      var status = 'updated';
      await axios.put(`/api/users/${form.id}`, form)
    } else {
      var status = 'added';
      await axios.post('/api/users', form)
    }


    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: `User ${status} successfully`,
      showConfirmButton: false,
      timer: 2000
    })
    resetForm()
    getUsers()
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

const editUser = (user) => {
  Object.assign(form, user)
}

const deleteUser = async (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'Do you want to Delete This Record?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  }).then(async (result) => {

    if (result.isConfirmed) {
      var data = await axios.delete(`/api/users/${id}`)

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

      getUsers()
      resetForm()
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `User deleted successfully`,
        showConfirmButton: false,
        timer: 2000
      })
    }

  })
}

const resetForm = () => {
  form.id = null
  form.name = ''
  form.email = ''
  form.phone = ''
  form.address = ''
}

onMounted(() => {
  getUsers()
})
</script>