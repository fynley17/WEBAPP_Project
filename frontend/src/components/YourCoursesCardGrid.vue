<template>
  <div class="container mt-4">
    <h2 class="mb-4">Your Courses</h2>
    <div class="row p-3 rounded mb-4" style="background-color: #2d212f">
      <div class="col" v-for="course in courses.filter(course => new Date(course.cDate) >= new Date())" :key="course.assignmentID">
        <div class="card h-100 small-card m-0">
          <div class="card-body">
            <!-- Title (top left) and Date (top right) -->
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
              <p class="card-text text-end mb-1"><strong>{{ course.cDate }}</strong></p>
            </div>
            
            <!-- Duration (left) and Current Attendance / Max Attendees (right) -->
            <div class="d-flex justify-content-between">
              <p class="card-text mb-1"><strong>Duration:</strong> {{ course.cDuration }} days</p>
              <p class="card-text text-end mb-1">
                <strong>{{ course.currentAttendence }} / {{ course.maxAttendees }}</strong>
              </p>
            </div>

            <!-- Description with fade effect for overflow -->
            <p class="card-text truncate-text" :title="course.cDescription">{{ course.cDescription }}</p>
          </div>
          <div class="card-footer p-2 d-flex justify-content-between">
            <button class="btn btn-sm" id="view" @click="openModal(course)">View Details</button>
            <button class="btn btn-sm" id="delete" @click="Delete(course.assignmentID)">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <h2 class="mb-4">Your Past Courses</h2>
    <div class="row p-3 rounded" style="background-color: #2d212f">
      <div class="col" v-for="course in courses.filter(course => new Date(course.cDate) <= new Date())" :key="course.assignmentID">
        <div class="card h-100 small-card m-0">
          <div class="card-body">
            <!-- Title (top left) and Date (top right) -->
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
              <p class="card-text text-end mb-1"><strong>{{ course.cDate }}</strong></p>
            </div>
            
            <!-- Duration (left) and Current Attendance / Max Attendees (right) -->
            <div class="d-flex justify-content-between">
              <p class="card-text mb-1"><strong>Duration:</strong> {{ course.cDuration }} days</p>
              <p class="card-text text-end mb-1">
                <strong>{{ course.currentAttendence }} / {{ course.maxAttendees }}</strong>
              </p>
            </div>

            <!-- Description with fade effect for overflow -->
            <p class="card-text truncate-text" :title="course.cDescription">{{ course.cDescription }}</p>
          </div>
          <div class="card-footer p-2 d-flex justify-content-between">
            <button class="btn btn-sm" id="view" @click="openModal(course)">View Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal to show full course information or delete message -->
  <Modal 
    :showModal="showModal" 
    @update:showModal="showModal = $event" 
    :viewCourse="viewCourse"
    :selectedCourse="selectedCourse" 
    :message="modalMessage">
  </Modal>
</template>

<script>
  import api from '../services/api';
  import Modal from './Modal.vue';

  export default {
    props: {
      username: String
    },
    data() {
      return {
        courses: [],
        showModal: false,
        modalMessage: '',
        selectedCourse: null,
        viewCourse: false
      };
    },
    watch: {
      username: {
        immediate: true,
        handler(newusername) {
          if (newusername) {
            this.fetchCourses(newusername);
          }
        }
      }
    },
    methods: {
      async fetchCourses(username) {
        try {
          console.log("Fetching courses for username:", username);
          const response = await api.get(`/assignments/${username}`);
          console.log('API Response:', response.data);
          this.courses = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching courses:', error);
        }
      },
      async Delete(assignmentID) {
        try {
          const response = await api.delete(`/assignments/${assignmentID}`);

          this.modalMessage = response.data.message;
          this.viewCourse = false;  
          this.selectedCourse = null;  
          this.showModal = true;

          this.courses = this.courses.filter(course => course.assignmentID !== assignmentID);
          this.$emit('course-unregistered');
        } catch (error) {
          console.error("Error deleting assignment:", error);
          this.modalMessage = "Error deleting the assignment.";
          this.viewCourse = false;
          this.selectedCourse = null;
          this.showModal = true;
        }
      },
      openModal(course) {
        this.selectedCourse = course;
        this.viewCourse = true;
        this.showModal = true;
      }
    },
    components: {
      Modal
    }
  };
</script>

<style scoped>
  .row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px; /* Adjust the gap as needed */
  }

  .col {
    display: flex;
    justify-content: center;
  }

  .card {
    margin-bottom: 0;
    background-color: #4b3f52; /* Set the background color of the card */
    color: white; /* Set the text color to white */
  }

  .small-card {
    font-size: 0.9rem;
    width: 100%;
    max-width: 250px;
    height: 200px !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    background-color: #4b3f52; /* Set the background color of the small card */
    color: white; /* Set the text color to white */
  }

  .card-body {
    flex-grow: 1;
    overflow-y: auto;
  }

  .card-body p {
    font-size: 0.8rem;
    margin-bottom: 2px;
  }

  .card-title {
    font-size: 1rem;
  }

  /* Add fading effect to overflowing text */
  .truncate-text {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    -webkit-line-clamp: 2; /* Limit the text to 2 lines */
    position: relative;
  }

  .truncate-text::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2em;  /* Height of the fading area */
    background: linear-gradient(to bottom, rgba(75, 63, 82, 0), rgba(75, 63, 82, 1)); /* Adjust gradient to match card background */
  }

  .card-footer {
    background-color: #2C272E; /* Set the background color of the card footer */
  }

  #view {
    background-color: #753188; /* Set the background color of the View Details button */
    color: white; /* Set the text color to white */
  }

  #view:hover {
    background-color: #4b1f57; /* Change the background color on hover */
  }

  #delete {
    color: #E59934;
    border-color: #E59934;
  }

  #delete:hover{
    color: #2C272E;
    background-color: #E59934;
  }

  @media (max-width: 992px) {
    .row {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 576px) {
    .row {
      grid-template-columns: 1fr;
    }
  }
</style>
