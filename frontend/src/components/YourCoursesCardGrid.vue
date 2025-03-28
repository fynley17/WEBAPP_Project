<template>
  <div class="container mt-4">
    <h2 class="mb-4">Your Courses</h2>
    <!-- Container for upcoming courses -->
    <div class="row p-3 rounded mb-4" style="background-color: #2d212f">
      <!-- Loop through and display only upcoming courses -->
      <div class="col" v-for="course in courses.filter(course => new Date(course.cDate) >= new Date())" :key="course.assignmentID">
        <div class="card h-100 small-card m-0">
          <div class="card-body">
            <!-- Display course title (left) and date (right) -->
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
              <p class="card-text text-end mb-1"><strong>{{ course.cDate }}</strong></p>
            </div>
            
            <!-- Display course duration and attendance -->
            <div class="d-flex justify-content-between">
              <p class="card-text mb-1"><strong>Duration:</strong> {{ course.cDuration }} days</p>
              <p class="card-text text-end mb-1">
                <strong>{{ course.currentAttendence }} / {{ course.maxAttendees }}</strong>
              </p>
            </div>

            <!-- Display course description with truncation for overflow -->
            <p class="card-text truncate-text" :title="course.cDescription">{{ course.cDescription }}</p>
          </div>
          <div class="card-footer p-2 d-flex justify-content-between">
            <!-- Button to view course details -->
            <button class="btn btn-sm" id="view" @click="openModal(course)">View Details</button>
            <!-- Button to delete the course -->
            <button class="btn btn-sm" id="delete" @click="Delete(course.assignmentID)">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <h2 class="mb-4">Your Past Courses</h2>
    <!-- Container for past courses -->
    <div class="row p-3 rounded" style="background-color: #2d212f">
      <!-- Loop through and display only past courses -->
      <div class="col" v-for="course in courses.filter(course => new Date(course.cDate) <= new Date())" :key="course.assignmentID">
        <div class="card h-100 small-card m-0">
          <div class="card-body">
            <!-- Display course title (left) and date (right) -->
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">{{ course.cTitle }}</h6>
              <p class="card-text text-end mb-1"><strong>{{ course.cDate }}</strong></p>
            </div>
            
            <!-- Display course duration and attendance -->
            <div class="d-flex justify-content-between">
              <p class="card-text mb-1"><strong>Duration:</strong> {{ course.cDuration }} days</p>
              <p class="card-text text-end mb-1">
                <strong>{{ course.currentAttendence }} / {{ course.maxAttendees }}</strong>
              </p>
            </div>

            <!-- Display course description with truncation for overflow -->
            <p class="card-text truncate-text" :title="course.cDescription">{{ course.cDescription }}</p>
          </div>
          <div class="card-footer p-2 d-flex justify-content-between">
            <!-- Button to view course details -->
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
    :message="modalMessage"
    @reset-selected-course="resetSelectedCourse">
  </Modal>
</template>

<script>
  import api from '../services/api';
  import Modal from './Modal.vue';

  export default {
    props: {
      username: String // Username passed as a prop
    },
    data() {
      return {
        courses: [], // List of courses
        showModal: false, // Controls the visibility of the modal
        modalMessage: '', // Message to display in the modal
        selectedCourse: null, // Currently selected course
        viewCourse: false // Indicates whether the modal is in view mode
      };
    },
    watch: {
      username: {
        immediate: true,
        handler(newusername) {
          if (newusername) {
            this.fetchCourses(newusername); // Fetch courses when username changes
          }
        }
      }
    },
    methods: {
      /**
       * Fetch the list of courses for the given username.
       * @param {string} username - The username to fetch courses for.
       */
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
      /**
       * Delete a course assignment by its ID.
       * @param {number} assignmentID - The ID of the assignment to delete.
       */
      async Delete(assignmentID) {
        try {
          const response = await api.delete(`/assignments/${assignmentID}`);

          this.modalMessage = response.data.message; // Display success message
          this.viewCourse = false;  
          this.selectedCourse = null;  
          this.showModal = true;

          // Remove the deleted course from the list
          this.courses = this.courses.filter(course => course.assignmentID !== assignmentID);
          this.$emit('course-unregistered'); // Notify parent component
        } catch (error) {
          console.error("Error deleting assignment:", error);
          this.modalMessage = "Error deleting the assignment.";
          this.viewCourse = false;
          this.selectedCourse = null;
          this.showModal = true;
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
    justify-content: center; /* center align the cards */
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

  /* Delete button styling */
  #delete {
    color: #E59934;
    border-color: #E59934;
  }

  #delete:hover {
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
