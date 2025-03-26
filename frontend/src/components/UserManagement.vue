<template>
  <div class="container mt-4 rounded">
    <h2 class="mb-4">User Management</h2>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Job</th>
            <th>Access Level</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr v-for="user in users" :key="user.userID">
            <td>{{ user.userID }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.firstName }}</td>
            <td>{{ user.lastName }}</td>
            <td>{{ user.jobTitle }}</td>
            <td>{{ user.accessLevel }}</td>
            <td class="actions-cell">
              <!-- Edit and Delete Buttons -->
              <button class="btn" id="action" @click="editUser(user)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn" id="action" @click="deleteUser(user.userID)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <button class="btn p-1 mb-2" id="add" @click="addUser">Add User</button>
  </div>

  <Modal 
    :showModal="showModal" 
    @update:showModal="showModal = $event" 
    :selectedCourse="selectedCourse" 
    :isAddingUser="isAddingUser" 
    @update:isAddingUser="isAddingUser = $event"
    :selectedUser="selectedUser"
    :isEditingUser="isEditingUser" 
    @update:isEditingUser="isEditingUser = $event"
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
      isAddingUser: false,
      isEditingUser: false,
      selectedUser: null
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
    },
    editUser(user) {
      console.log(user);
      this.isEditingUser = true;
      this.selectedUser = user;
      this.showModal = true;
    },
    async deleteUser(userID) {
      try {
        const response = await api.delete(`/users/${userID}`);
        console.log('User deleted:', response.data);
        this.fetchUsers(); // Refresh the user list after deletion
      } catch (error) {
        console.error('Error deleting user:', error);
      }
    }
  },
  components: {
    Modal
  }
};
</script>

<style scoped>
  .container {
    background-color: #2C272E;
    padding: 20px;
    border-radius: 8px;
  }

  .custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    border-radius: 8px;
    overflow: hidden;
  }

  .custom-table th, .custom-table td {
    border: 1px solid #000000;
    padding: 8px;
    text-align: center; /* Center align text in table cells */
  }

  .custom-table th {
    background-color: #753188;
    color: white;
  }

  .custom-table tbody tr:nth-child(even) {
    background-color: #2C272E;
  }

  .custom-table tbody tr:hover {
    background-color: #4b3f52;
    transition: 0.3s;
  }

  .actions-cell {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  #action {
    color: #E59934;
    border: none;
    background: none;
    cursor: pointer;
  }

  #action:hover {
    background-color: #493111;
  }

  #add {
    background-color: #753188;
    color: white;
    border-radius: 5px;
  }

  #add:hover {
    background-color: #5a2566;
  }
</style>