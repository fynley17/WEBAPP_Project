<template>
    <div class="container mt-4">
      <h2 class="mb-4">Assignment Management</h2>
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

              <button class="btn btn-sm" @click="deleteAssignment(assignment.assignmentID)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="btn btn-primary p-1 mb-2" @click="addAssignment()">Add Assignment</button>
    </div>

    <Modal 
      :showModal="showModal" 
      @update:showModal="showModal = $event" 
      :selectedAssignment="selectedAssignment"
      :isAddingAssignment="isAddingAssignment" 
      @update:isAddingAssignment="isAddingAssignment = $event"
      :isEditingAssignment="isEditingAssignment" 
      @update:isEditingAssignment="isEditingAssignment = $event"
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
        selectedAssignment: null,
        isAddingAssignment: false,
        isEditingAssignment: false,
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
          this.assignments = (Array.isArray(response.data) ? response.data : [response.data])
          .sort((a, b) => a.assignmentID - b.assignmentID);
        } catch (error) {
          console.error('Error fetching assignments:', error);
        }
      },
      addAssignment() {
        this.isAddingAssignment = true; 
        this.showModal = true;
      },
      editAssignment(assignment) {
        this.isEditingAssignment = true;
        this.selectedAssignment = assignment; // Fix: Correct the variable name here
        this.showModal = true;
      },
      async deleteAssignment(assignmentID) {
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
  