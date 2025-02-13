<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Vortech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul v-if="$route.path === '/admin'" class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <button class="nav-link btn" @click="$emit('change-tab', 'yourCourses')">Your Courses</button>
            </li>
            <li class="nav-item">
              <button class="nav-link btn" @click="$emit('change-tab', 'userManagement')">User Management</button>
            </li>
            <li class="nav-item">
              <button class="nav-link btn" @click="$emit('change-tab', 'courseManagement')">Course Management</button>
            </li>
          </ul>
        </div>
        <button class="d-flex btn btn-outline-danger" @click="logout()">Log Out</button>
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    methods: {
      logout() {
        localStorage.removeItem('token');
        this.$router.push('/');
      }
    }
  };
  </script>
  