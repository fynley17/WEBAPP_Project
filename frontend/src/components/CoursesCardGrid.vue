<template>
  <div class="container mt-4 mb-4">
    <h2 class="mb-4">All Courses</h2>
    <!-- Course grid container -->
    <div class="row p-3 rounded" style="background-color: #2d212f">
      <!-- Loop through courses and display only upcoming ones -->
      <div class="col" v-for="course in courses.filter(course => new Date(course.cDate) >= new Date())" :key="course.courseID">
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
            <!-- Button to view course details -->
            <button class="btn btn-sm" id="view" @click="openModal(course)">View Details</button>
            <!-- Button to register for the course -->
            <button class="btn btn-sm" id="delete" @click="Register(course.courseID)" :disabled="course.currentAttendence === course.maxAttendees">Register</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal to show full course information or registration message -->
  <Modal 
    :showModal="showModal" 
    @update:showModal="showModal = $event" 
    :selectedCourse="selectedCourse" 
    :viewCourse="viewCourse"
    :message="modalMessage"
    @reset-selected-course="resetSelectedCourse">
  </Modal>
</template>

<script>
  import api from '../services/api';
  import Modal from './Modal.vue';

  export default {
    props: {
      userID: String // User ID passed as a prop
    },
    data() {
      return {
        courses: [], // List of all courses
        showModal: false, // Controls the visibility of the modal
        modalMessage: '', // Message to display in the modal
        selectedCourse: null, // Currently selected course
        viewCourse: false // Indicates whether the modal is in view mode
      };
    },
    mounted() {
      this.fetchCourses(); // Fetch courses when the component is mounted
    },
    methods: {
      /**
       * Fetch the list of courses from the API.
       */
      async fetchCourses() {
        try {
          const response = await api.get(`/courses`);
          this.courses = Array.isArray(response.data) ? response.data : [response.data];
        } catch (error) {
          console.error('Error fetching courses:', error); // Log any errors
        }
      },
      /**
       * Open the modal to view course details.
       * @param {Object} course - The course to view.
       */
      openModal(course) {
        this.selectedCourse = course;
        this.viewCourse = true;
        this.showModal = true;
      },
      /**
       * Reset the selected course and close the modal.
       */
      resetSelectedCourse() {
        this.selectedCourse = null;
        this.viewCourse = false;
      },
      /**
       * Register the user for a course.
       * @param {number} courseID - The ID of the course to register for.
       */
      async Register(courseID) {
        try {
          const response = await api.post("/assignments", {
            userID: this.userID,
            courseID: courseID,
          });

          this.modalMessage = response.data.message; // Display success message
          this.showModal = true;

          this.$emit('course-registered'); // Emit event to notify parent component
          this.fetchCourses(); // Refresh course data
        } catch (error) {
          console.error('Error registering assignment:', error); // Log any errors
          this.modalMessage = 'You are already assigned to this course.';

          // Reset course details before showing the modal
          this.viewCourse = false; 
          this.selectedCourse = null;

          this.showModal = true;
        }
      }
    },
    components: {
      Modal // Register the Modal component
    }
  };
</script>

<style scoped>
  /* Grid layout for courses */
  .row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px; /* Adjust the gap as needed */
  }

  .col {
    display: flex;
    justify-content: center;
  }

  /* Card styling */
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

  /* Footer styling */
  .card-footer {
    background-color: #2C272E; /* Set the background color of the card footer */
  }

  /* View Details button styling */
  #view {
    background-color: #753188; /* Set the background color of the View Details button */
    color: white; /* Set the text color to white */
  }

  #view:hover {
    background-color: #4b1f57; /* Change the background color on hover */
  }

  /* Register button styling */
  #delete {
    color: #E59934;
    border-color: #E59934;
  }

  #delete:hover{
    color: #2C272E;
    background-color: #E59934;
  }

  /* Responsive design for smaller screens */
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