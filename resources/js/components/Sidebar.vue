<template>
  <div class="sidebar d-flex flex-column p-3">
    <h4 class="mb-4 text-center fw-bold text-success">Dashboard</h4>

    <ul class="nav flex-column gap-2">
      <li>
        <router-link to="/home" class="nav-link" exact>
          <i class="bi bi-house"></i>
          <span>Home</span>
        </router-link>
      </li>

      <li>
        <router-link to="/users" class="nav-link">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </router-link>
      </li>

      <li>
        <router-link to="/products" class="nav-link">
          <i class="bi bi-box"></i>
          <span>Products</span>
        </router-link>
      </li>

      <li>
        <router-link to="/sales" class="nav-link">
          <i class="bi bi-cash"></i>
          <span>Sales</span>
        </router-link>
      </li>

      <li>
        <router-link to="/help" class="nav-link">
          <i class="bi bi-cash"></i>
          <span>Help (AI)</span>
        </router-link>
      </li>

    </ul>
  </div>
</template>

<style scoped>
.sidebar {
  width: 250px;
  height: 100vh;
  background: #111827;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #cbd5e1;
  padding: 10px 12px;
  border-radius: 8px;
  text-decoration: none;
  transition: 0.2s;
}

.nav-link:hover {
  background: #1f2937;
  color: #fff;
}

.router-link-active {
  background: #2563eb;
  color: #fff !important;
}

i {
  font-size: 18px;
}
</style>