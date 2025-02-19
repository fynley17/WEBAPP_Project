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
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import api from '../services/api';
  
  export default {
    name: "courseManagement",
    data() {
      return {
        courses: [],
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
  
  th,
  td {
    text-align: center;
    vertical-align: middle;
  }
  
  /* ✅ Ensures the Date column has more space and fits in one line */
  .date-column {
    min-width: 150px; /* Adjust width as needed */
    white-space: nowrap; /* Prevents wrapping */
  }
  </style>
  