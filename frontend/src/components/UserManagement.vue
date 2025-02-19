<template>
    <div class="container mt-4">
      <h2 class="mb-4"> User Management</h2>
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Email</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Job</th>
              <th>Access Level</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.userID">
              <td>{{ user.userID }}</td>
              <td>{{ user.username }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.firstName }}</td>
              <td>{{ user.lastName }}</td>
              <td>{{ user.jobTitle }}</td>
              <td>{{ user.accessLevel }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import api from '../services/api';
  
  export default {
    name: "UserManagement",
    data() {
      return {
        users: [],
      };
    },
    mounted() {
      this.fetchUsers(); 
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await api.get(`/users`);
          console.log('API Response:', response.data);
          this.users = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching users:', error);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .table {
    border-radius: 8px;
    overflow: hidden;
  }
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: 0.3s;
  }
  </style>
  