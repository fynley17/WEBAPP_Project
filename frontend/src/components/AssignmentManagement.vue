<template>
  <div class="container mt-4 rounded">
    <h2 class="mb-4">Assignment Management</h2>

    <!-- Search input -->
    <div class="mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Search by username, course title, or date"
        v-model="searchQuery"
        @input="filterAssignments"
      />
    </div>
    
    <button class="btn p-1 mb-2" id="add" @click="addAssignment">Add Assignment</button>

    <!-- Upcoming Assignments Table -->
    <h3>Upcoming Assignments</h3>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <th @click="sortBy('assignmentID', 'upcoming')" class="sortable">
              # <span v-if="sortKey === 'assignmentID' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('username', 'upcoming')" class="sortable">
              Username <span v-if="sortKey === 'username' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cTitle', 'upcoming')" class="sortable">
              Course Title <span v-if="sortKey === 'cTitle' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cDate', 'upcoming')" class="sortable">
              Course Date <span v-if="sortKey === 'cDate' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr v-for="assignment in filteredUpcomingAssignments" :key="assignment.assignmentID">
            <td>{{ assignment.assignmentID }}</td>
            <td>{{ assignment.username }}</td>
            <td>{{ assignment.cTitle }}</td>
            <td>{{ assignment.cDate }}</td>
            <td class="actions-cell">
              <button
                class="btn"
                id="action"
                @click="deleteAssignment(assignment.assignmentID)"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Past Assignments Table -->
    <h3>Past Assignments</h3>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <th @click="sortBy('assignmentID', 'past')" class="sortable">
              # <span v-if="sortKey === 'assignmentID' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('username', 'past')" class="sortable">
              Username <span v-if="sortKey === 'username' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cTitle', 'past')" class="sortable">
              Course Title <span v-if="sortKey === 'cTitle' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cDate', 'past')" class="sortable">
              Course Date <span v-if="sortKey === 'cDate' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr v-for="assignment in filteredPastAssignments" :key="assignment.assignmentID">
            <td>{{ assignment.assignmentID }}</td>
            <td>{{ assignment.username }}</td>
            <td>{{ assignment.cTitle }}</td>
            <td>{{ assignment.cDate }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Button to add a new assignment -->
  </div>

  <!-- Modal component for managing assignments -->
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
    @assignment-added="fetchAssignments()"
  >
  </Modal>
</template>

<script>
import api from "../services/api";
import Modal from "./Modal.vue";

export default {
  name: "assignmentManagement",
  data() {
    return {
      assignments: [], // List of assignments
      filteredUpcomingAssignments: [], // Filtered list of upcoming assignments
      filteredPastAssignments: [], // Filtered list of past assignments
      searchQuery: "", // Search query for filtering
      sortKey: "", // Key to sort by
      sortOrder: 1, // Sort order: 1 for ascending, -1 for descending
      sortTarget: "upcoming", // Target table for sorting ('upcoming' or 'past')
      showModal: false, // Controls the visibility of the modal
      modalMessage: "", // Message to display in the modal
      selectedAssignment: null, // The currently selected assignment
      isAddingAssignment: false, // Indicates if a new assignment is being added
      isEditingAssignment: false, // Indicates if an assignment is being edited
      selectedUser: null, // The currently selected user
      isAddingUser: false, // Indicates if a new user is being added
      isEditingUser: false, // Indicates if a user is being edited
    };
  },
  mounted() {
    // Fetch assignments when the component is mounted
    this.fetchAssignments();
  },
  methods: {
    /**
     * Fetch the list of assignments from the API.
     */
    async fetchAssignments() {
      try {
        const response = await api.get(`/assignments`);
        this.assignments = Array.isArray(response.data) ? response.data : [response.data];
        this.filterAssignments(); // Filter assignments into upcoming and past
      } catch (error) {
        console.error("Error fetching assignments:", error); // Log any errors
      }
    },
    /**
     * Open the modal to add a new assignment.
     */
    addAssignment() {
      this.isAddingAssignment = true;
      this.showModal = true;
    },
    /**
     * Delete an assignment by its ID.
     * @param {number} assignmentID - The ID of the assignment to delete.
     */
    async deleteAssignment(assignmentID) {
      try {
        const response = await api.delete(`/assignments/${assignmentID}`);
        console.log("Assignment deleted:", response.data);
        this.fetchAssignments(); // Refresh the assignment list after deletion
      } catch (error) {
        console.error("Error deleting assignment:", error); // Log any errors
      }
    },
    /**
     * Filter assignments into upcoming and past based on the current date.
     */
    filterAssignments() {
      const today = new Date();
      const query = this.searchQuery.toLowerCase();

      this.filteredUpcomingAssignments = this.assignments.filter((assignment) => {
        const courseDate = new Date(assignment.cDate);
        return (
          courseDate >= today &&
          (assignment.username.toLowerCase().includes(query) ||
            assignment.cTitle.toLowerCase().includes(query) ||
            assignment.cDate.includes(query))
        );
      });

      this.filteredPastAssignments = this.assignments.filter((assignment) => {
        const courseDate = new Date(assignment.cDate);
        return (
          courseDate < today &&
          (assignment.username.toLowerCase().includes(query) ||
            assignment.cTitle.toLowerCase().includes(query) ||
            assignment.cDate.includes(query))
        );
      });
    },
    /**
     * Sort assignments by a given key for a specific table.
     * @param {string} key - The key to sort by (e.g., 'username', 'cTitle').
     * @param {string} target - The target table to sort ('upcoming' or 'past').
     */
    sortBy(key, target) {
      if (this.sortKey === key && this.sortTarget === target) {
        // If already sorting by this key in the same table, toggle the sort order
        this.sortOrder *= -1;
      } else {
        // Otherwise, set the new sort key and target, and default to ascending order
        this.sortKey = key;
        this.sortTarget = target;
        this.sortOrder = 1;
      }

      const targetArray =
        target === "upcoming" ? this.filteredUpcomingAssignments : this.filteredPastAssignments;

      targetArray.sort((a, b) => {
        if (a[key] < b[key]) return -1 * this.sortOrder;
        if (a[key] > b[key]) return 1 * this.sortOrder;
        return 0;
      });
    },
  },
  components: {
    Modal, // Register the Modal component
  },
};
</script>

<style scoped>
/* Container styling */
.container {
  background-color: #2d212f;
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
  padding-left: 8px;
  padding-right: 8px;
  text-align: center; /* Centre align text in table cells */
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
