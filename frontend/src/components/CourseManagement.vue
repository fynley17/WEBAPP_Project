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
            <tr v-for="course in courses" :key="course.courseID">
              <td>{{ course.courseID }}</td>
              <td>{{ course.cTitle }}</td>
              <td class="date-column">{{ course.cDate }}</td>
              <td>{{ course.cDuration }}</td>
              <td>{{ course.maxAttendees }}</td>
              <td>{{ course.currentAttendence }}</td>
              <td>{{ course.cDescription }}</td>
              <td>
              <!-- Edit and Delete Buttons with Bootstrap spacing -->
              <button class="btn btn-sm" @click="editCourse(course)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm" @click="deleteCourse(course.courseID)">
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
      @course-added="fetchCourses()">
    </Modal>

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
        isEditingUser: false
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
        this.selectedCourse = course; // Fix: Correct the variable name here
        this.showModal = true;
      },
      async deleteCourse(courseID) {
        try {
          const response = await api.delete(`/courses/${courseID}`);
          console.log('course deleted:', response.data);
          this.fetchCourses(); // Refresh the course list after deletion
        } catch (error) {
          console.error('Error deleting course:', error);
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
  