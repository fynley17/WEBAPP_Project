<template>
  <div class="container mt-4">
    <h2 class="mb-4">Course Management</h2>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th class="date-column">Date</th>
            <th>Duration</th>
            <th>Max Attendance</th>
            <th>Current Attendance</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="course in courses" :key="course.courseID">
            <!-- Main Row -->
            <tr @click="toggleRow(course.courseID)">
              <td>{{ course.courseID }}</td>
              <td>{{ course.cTitle }}</td>
              <td class="date-column">{{ course.cDate }}</td>
              <td>{{ course.cDuration }}</td>
              <td>{{ course.maxAttendees }}</td>
              <td>{{ course.currentAttendence }}</td>
              <td>{{ course.cDescription }}</td>
              <td>
                <button class="btn btn-sm" @click.stop="editCourse(course)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm" @click.stop="deleteCourse(course.courseID)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>

            <!-- Expandable Row -->
            <tr v-if="expandedRows.includes(course.courseID)" class="expandable-row">
              <td colspan="7">
                <div class="expanded-content">
                  <h5>Enrolled Users</h5>
                  <table class="table table-sm table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in course.assignedUsers" :key="user.userID">
                        <td>{{ user.userID }}</td>
                        <td>{{ user.username }}</td>
                        <td>
                          <button class="btn btn-sm" @click.stop="deleteUser(user.assignmentID)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="course.assignedUsers.length === 0">
                        <td colspan="2">No users assigned</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <button class="btn btn-primary p-1 mb-2" @click="addCourse()">Add Course</button>

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
      @course-added="fetchCourses()">
    </Modal>
  </div>
</template>

  
<script>
  import api from '../services/api';
  import Modal from './Modal.vue';

  export default {
    name: "courseManagement",
    data() {
      return {
        courses: [],
        showModal: false,
        modalMessage: '',
        selectedCourse: null,
        isAddingCourse: false,
        isEditingCourse: false,
        selectedUser: null,
        isAddingUser: false,
        isEditingUser: false,
        expandedRows: [] // Track expanded rows
      };
    },
    mounted() {
      this.fetchCourses();
    },
    methods: {
      async fetchCourses() {
        try {
          const response = await api.get(`/courses`);
          console.log('API Response:', response.data);
          this.courses = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching courses:', error);
        }
      },
      addCourse() {
        this.isAddingCourse = true; 
        this.showModal = true;
      },
      editCourse(course) {
        this.isEditingCourse = true;
        this.selectedCourse = course;
        this.showModal = true;
      },
      async deleteCourse(courseID) {
        try {
          const response = await api.delete(`/courses/${courseID}`);
          console.log('Course deleted:', response.data);
          this.fetchCourses(); // Refresh the course list after deletion
        } catch (error) {
          console.error('Error deleting course:', error);
        }
      },
      async deleteUser(assignmentID) {
        try {
          const response = await api.delete(`/assignments/${assignmentID}`);
          console.log('Assignment deleted:', response.data);
          
          // Fetch the course and manually update its users after deletion
          const course = this.courses.find(c => c.assignedUsers.some(u => u.assignmentID === assignmentID));
          if (course) {
            // Remove the user from the course's assignedUsers array
            course.assignedUsers = course.assignedUsers.filter(user => user.assignmentID !== assignmentID);
          }
        } catch (error) {
          console.error('Error deleting assignment:', error);
        }
      },
      async toggleRow(courseID) {
        const index = this.expandedRows.indexOf(courseID);
        if (index === -1) {
          // Expand the row
          this.expandedRows.push(courseID);
          
          // Find the course and fetch users
          const course = this.courses.find(c => c.courseID === courseID);
          if (course && !course.assignedUsers) {
            course.assignedUsers = []; // Initialize the array to avoid reactivity issues
            await this.fetchAssignedUsers(course);
          }
        } else {
          // Collapse the row
          this.expandedRows.splice(index, 1);
        }
      },
      async fetchAssignedUsers(course) {
        try {
          const response = await api.get(`/assignedUsers/${course.courseID}`);
          console.log(`Users for course ${course.courseID}:`, response.data);
          course.assignedUsers = response.data;
        } catch (error) {
          console.error(`Error fetching users for course ${course.courseID}:`, error);
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
  