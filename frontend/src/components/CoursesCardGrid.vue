<template>
  <div class="container mt-4">
    <h2 class="mb-4">All Courses</h2>
    <div class="row p-3 rounded" style="background-color: #f4f4f4">
      <div class="col" v-for="course in courses" :key="course.courseID">
        <div class="card h-100 small-card m-0">
          <div class="card-body">
            <!-- Title (top left) and Date (top right) -->
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
              <p class="card-text text-end mb-1 nowrap"><strong>{{ course.cDate }}</strong></p>
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
            <button class="btn btn-sm btn-primary" @click="openModal(course)">View Details</button>
            <button class="btn btn-sm btn-secondary" @click="Register(course.courseID)" :disabled="course.currentAttendence === course.maxAttendees">Register</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal to show full course information or delete message -->
  <Modal 
    :showModal="showModal" 
    @update:showModal="showModal = $event" 
    :selectedCourse="selectedCourse" 
    :viewCourse="viewCourse"
    :message="modalMessage">
  </Modal>
</template>

<script>
  import api from '../services/api';
  import Modal from './Modal.vue';

  export default {
    props: {
      userID: String
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
    mounted() {
      this.fetchCourses();
    },
    methods: {
      async fetchCourses() {
        try {
          const response = await api.get(`/courses`);
          this.courses = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching courses:', error);
        }
      },
      openModal(course) {
        this.selectedCourse = course;
        this.viewCourse = true;
        this.showModal = true;
      },
      async Register(courseID) {
        try {
          const response = await api.post("/assignments", {
            userID: this.userID,
            courseID: courseID,
          });

          this.modalMessage = response.data.message;
          this.showModal = true;

          this.$emit('course-registered'); // Emit event
          this.fetchCourses();  // Refresh course data
        } catch (error) {
          console.error('Error registering assignment:', error);
          this.modalMessage = 'You are already assigned to this course.';
          this.showModal = true;
        }
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

  .nowrap {
    white-space: nowrap;
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
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
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
