<template>
  <div class="container mt-4 rounded">
    <h2 class="mb-4">Assignment Management</h2>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Course Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr v-for="assignment in assignments" :key="assignment.assignmentID">
            <td>{{ assignment.assignmentID }}</td>
            <td>{{ assignment.username }}</td>
            <td>{{ assignment.cTitle }}</td>
            <td class="actions-cell">
              <button class="btn" id="action" @click="deleteAssignment(assignment.assignmentID)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <button class="btn p-1 mb-2" id="add" @click="addAssignment">Add Assignment</button>
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
.container {
  background-color: #2d212f;
  padding: 20px;
  border-radius: 8px;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  border-radius: 10px;
  overflow: hidden;
}

.custom-table th, .custom-table td {
  border: 1px solid #302835;
  padding-left: 8px;
  padding-right: 8px;
  text-align: center; /* Center align text in table cells */
}

.custom-table th {
  background-color: #2C272E;
  color: white;
}

.custom-table tbody tr {
  background-color: #4b3f52;
}

.custom-table tbody tr:hover {
  background-color: #3e3444;
  transition: 0.3s;
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
