<template>
    <div class="container mt-4">
      <h2 class="mb-4">Course Management</h2>
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>username</th>
              <th>course Title</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="assignment in assignments" :key="assignment.assignmentID">
              <td>{{ assignment.assignmentID }}</td>
              <td>{{ assignment.username }}</td>
              <td>{{ assignment.cTitle }}</td>
              <td>
              <!-- Edit and Delete Buttons with Bootstrap spacing -->
              <button class="btn btn-sm" @click="editCourse(assignment)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm" @click="deleteCourse(assignment.assignmentID)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="btn btn-primary p-1 mb-2" @click="addCourse()">Add Course</button>
    </div>

    <Modal 
      :showModal="showModal" 
      @update:showModal="showModal = $event" 
      :selectedCourse="selectedCourse"
      :isAddingCourse="isAddingCourse" 
      @update:isAddingCourse="isAddingCourse = $event"
      :isEditingCourse="isEditingCourse" 
      @update:isEditingCourse="isEditingCourse = $event"
      :isAddingUser="isAddingUser" 
      @update:isAddingUser="isAddingUser = $event"
      :selectedUser="selectedUser"
      :isEditingUser="isEditingUser" 
      @update:isEditingUser="isEditingUser = $event"
      :message="modalMessage"
      @assignment-added="fetchAssignments()">
    </Modal>

  </template>
  
  <script>
  import api from '../services/api';
  import Modal from './Modal.vue';
  
  export default {
    name: "assignmentManagement",
    data() {
      return {
        assignments: [],
        showModal: false,
        modalMessage: '',
        selectedCourse: null,
        isAddingCourse: false,
        isEditingCourse: false,
        selectedUser: null,
        isAddingUser: false,
        isEditingUser: false
      };
    },
    mounted() {
      this.fetchAssignments();
    },
    methods: {
      async fetchAssignments() {
        try {
          const response = await api.get(`/assignments`);
          console.log('API Response:', response.data);
          this.assignments = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching assignments:', error);
        }
      },
      addCourse() {
        this.isAddingCourse = true; 
        this.showModal = true;
      },
      editCourse(assignment) {
        this.isEditingCourse = true;
        this.selectedCourse = assignment; // Fix: Correct the variable name here
        this.showModal = true;
      },
      async deleteCourse(assignmentID) {
        try {
          const response = await api.delete(`/assignments/${assignmentID}`);
          console.log('assignment deleted:', response.data);
          this.fetchAssignments(); // Refresh the assignment list after deletion
        } catch (error) {
          console.error('Error deleting assignment:', error);
        }
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
  
  th,
  td {
    text-align: center;
    vertical-align: middle;
  }
  
  .date-column {
    min-width: 150px; /* Adjust width as needed */
    white-space: nowrap; /* Prevents wrapping */
  }
  </style>
  