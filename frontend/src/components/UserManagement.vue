<template>
  <div class="container mt-4 rounded">
    <h2 class="mb-4">User Management</h2>

    <!-- Search input -->
    <div class="mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Search by username, email, or name"
        v-model="searchQuery"
        @input="filterUsers"
      />
    </div>

    <button class="btn p-1 mb-2" id="add" @click="addUser">Add User</button>

    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <!-- Sortable column for User ID -->
            <th @click="sortBy('userID')" class="sortable">
              # <span v-if="sortKey === 'userID'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for Username -->
            <th @click="sortBy('username')" class="sortable">
              Username <span v-if="sortKey === 'username'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for Email -->
            <th @click="sortBy('email')" class="sortable">
              Email <span v-if="sortKey === 'email'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for First Name -->
            <th @click="sortBy('firstName')" class="sortable">
              First Name <span v-if="sortKey === 'firstName'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for Last Name -->
            <th @click="sortBy('lastName')" class="sortable">
              Last Name <span v-if="sortKey === 'lastName'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for Job Title -->
            <th @click="sortBy('jobTitle')" class="sortable">
              Job <span v-if="sortKey === 'jobTitle'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <!-- Sortable column for Access Level -->
            <th @click="sortBy('accessLevel')" class="sortable">
              Access Level <span v-if="sortKey === 'accessLevel'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <!-- Loop through filtered users and display them -->
          <tr v-for="user in filteredUsers" :key="user.userID">
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

    <!-- Modal component for managing users -->
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
  </div>
</template>

<script>
import api from '../services/api';
import Modal from './Modal.vue';

export default {
  name: "UserManagement",
  data() {
    return {
      users: [], // List of all users
      filteredUsers: [], // Filtered and sorted users
      searchQuery: "", // Search query for filtering
      sortKey: "", // Key to sort by
      sortOrder: 1, // Sort order: 1 for ascending, -1 for descending
      showModal: false, // Controls the visibility of the modal
      modalMessage: '', // Message to display in the modal
      selectedCourse: null, // Currently selected course
      isAddingUser: false, // Indicates if a new user is being added
      isEditingUser: false, // Indicates if a user is being edited
      selectedUser: null // Currently selected user
    };
  },
  mounted() {
    this.fetchUsers(); // Fetch users when the component is mounted
  },
  methods: {
    /**
     * Fetch the list of users from the API.
     */
    async fetchUsers() {
      try {
        const response = await api.get(`/users`);
        console.log('API Response:', response.data);
        this.users = Array.isArray(response.data) ? response.data : [response.data];
        this.filteredUsers = this.users; // Initialise filtered users
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    /**
     * Open the modal to add a new user.
     */
    addUser() {
      this.isAddingUser = true; 
      this.showModal = true;
    },
    /**
     * Open the modal to edit an existing user.
     * @param {Object} user - The user to edit.
     */
    editUser(user) {
      console.log(user);
      this.isEditingUser = true;
      this.selectedUser = user;
      this.showModal = true;
    },
    /**
     * Delete a user by their ID.
     * @param {number} userID - The ID of the user to delete.
     */
    async deleteUser(userID) {
      try {
        const response = await api.delete(`/users/${userID}`);
        console.log('User deleted:', response.data);
        this.fetchUsers(); // Refresh the user list after deletion
      } catch (error) {
        console.error('Error deleting user:', error);
      }
    },
    /**
     * Filter users based on the search query.
     */
    filterUsers() {
      const query = this.searchQuery.toLowerCase();
      this.filteredUsers = this.users.filter(
        (user) =>
          user.username.toLowerCase().includes(query) ||
          user.email.toLowerCase().includes(query) ||
          user.firstName.toLowerCase().includes(query) ||
          user.lastName.toLowerCase().includes(query) ||
          user.jobTitle.toLowerCase().includes(query) ||
          user.accessLevel.toString().toLowerCase().includes(query)
      );
    },
    /**
     * Sort users by a given key.
     * @param {string} key - The key to sort by (e.g., 'username', 'email').
     */
    sortBy(key) {
      if (this.sortKey === key) {
        // If already sorting by this key, toggle the sort order
        this.sortOrder *= -1;
      } else {
        // Otherwise, set the new sort key and default to ascending order
        this.sortKey = key;
        this.sortOrder = 1;
      }
      this.filteredUsers.sort((a, b) => {
        if (a[key] < b[key]) return -1 * this.sortOrder;
        if (a[key] > b[key]) return 1 * this.sortOrder;
        return 0;
      });
    }
  },
  components: {
    Modal
  }
};
</script>

<style scoped>
/* Container styling */
.container {
  background-color: #2d212f; /* Set the background color of the container */
  padding: 20px;
  border-radius: 8px;
}

/* Table styling */
.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  border-radius: 10px;
  overflow: hidden;
}

.custom-table th, .custom-table td {
  border: 1px solid #302835;
  padding-left: 4px;
  padding-right: 4px;
  text-align: center; /* center align text in table cells */
}

.custom-table th {
  background-color: #2C272E; /* Set the background color of table headers */
  color: white; /* Set the text color to white */
}

.custom-table tbody tr {
  background-color: #4b3f52; /* Set the background color of table rows */
}

.custom-table tbody tr:hover {
  background-color: #3e3444; /* Change the background color on hover */
  transition: 0.3s;
}

/* Delete button styling */
#action {
  color: #E59934;
  border: none;
  background: none;
  cursor: pointer;
}

#action:hover {
  background-color: #493111;
}

/* Add button styling */
#add {
  background-color: #753188;
  color: white;
  border-radius: 5px;
}

#add:hover {
  background-color: #5a2566;
}

/* Styling for sortable table headers */
.sortable {
  cursor: pointer;
  position: relative;
}

.sortable::after {
  content: "⇅"; /* Default indicator for sortable columns */
  font-size: 0.8rem;
  margin-left: 5px;
  color: #ccc;
}

.sortable.asc::after {
  content: "↑"; /* Ascending order indicator */
  color: #000;
}

.sortable.desc::after {
  content: "↓"; /* Descending order indicator */
  color: #000;
}
</style>