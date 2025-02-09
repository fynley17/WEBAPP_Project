<template>
    <div class="container mt-4">
      <h2 class="mb-4">Your Courses</h2>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 p-3" style="background-color: lightgrey;">
        <div class="col mt-0" v-for="course in courses" :key="course.username">
          <div class="card h-100 small-card">
            <div class="card-body">
              <!-- Title (top left) and Date (top right) -->
              <div class="d-flex justify-content-between">
                <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
                <p class="card-text text-end mb-1"><strong>Date:</strong> {{ course.cDate }}</p>
              </div>
              
              <!-- Duration (left) and Current Attendance / Max Attendees (right) -->
              <div class="d-flex justify-content-between">
                <p class="card-text mb-1"><strong>Duration:</strong> {{ course.cDuration }} days</p>
                <p class="card-text text-end mb-1">
                  <strong>{{ course.currentAttendence }} / {{ course.maxAttendees }}</strong>
                </p>
              </div>
              
              <!-- Description -->
              <p class="card-text">{{ course.cDescription }}</p>
            </div>
            <div class="card-footer p-2 d-flex justify-content-between">
              <button class="btn btn-sm btn-primary">View Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal :showModal="showModal" :message="modalMessage" @update:showModal="showModal = $event" />
  </template>
  
  <style>
  .small-card {
    font-size: 0.9rem;
    width: 250px;   /* Fixed width */
    height: 200px !important;  /* Use !important to enforce height */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;  /* Prevent any overflow if content exceeds height */
  }
  
  .card-body {
    flex-grow: 1; /* Ensures content takes up remaining space in the card */
    overflow-y: auto;  /* Ensure content scrolls if it exceeds the body height */
  }
  
  .card-body p {
    font-size: 0.8rem;
    margin-bottom: 2px;
  }
  
  .card-title {
    font-size: 1rem;
  }
  </style>
  
  
<script>
  import api from '../services/api'; 
  import Modal from './Modal.vue';
  
  export default {
    props: {
      username: String // Accept username as a prop
    },
    data() {
      return {
        courses: [],
        showModal: false,
        modalMessage: ''
      };
    },
    methods: {
      async fetchCourses(username) {
        try {
          console.log("Fetching courses for username:", username); // Debugging
          const response = await api.get(`/courses`);
          console.log('API Response:', response.data);
          this.courses = Array.isArray(response.data) ? response.data : [response.data]; 
        } catch (error) {
          console.error('Error fetching courses:', error);
        }
      }
    },
    components: {
      Modal  // Register the Modal component
    }
  };
  </script>

  