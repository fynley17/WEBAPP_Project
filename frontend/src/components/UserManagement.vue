<template>
    <div class="container mt-4 rounded border border-black shadow-lg">
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
      <button class="btn btn-primary p-1 mb-2" @click="addUser">Add User</button>
    </div>

    <Modal 
      :showModal="showModal" 
      @update:showModal="showModal = $event" 
      :selectedCourse="selectedCourse" 
      :isAddingUser="isAddingUser" 
      @update:isAddingUser="isAddingUser = $event"
      :message="modalMessage" @user-added="fetchUsers()">
    </Modal>
  </template>
  
  <script>
  import api from '../services/api';
  import Modal from './Modal.vue';
  
  export default {
    name: "UserManagement",
    data() {
      return {
        users: [],
        showModal: false,
        modalMessage: '',
        selectedCourse: null,
        isAddingUser: false
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
      addUser() {
        this.isAddingUser = true; 
        this.showModal = true;
      }
    },
    components: {
      Modal
    }
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
  