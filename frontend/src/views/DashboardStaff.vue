<template>
  <div id="navbar">
    <!-- Navigation bar component -->
    <Nav />
  </div>
  <div>
    <!-- Grid for the user's courses -->
    <YourCoursesCardGrid 
      :username="username" 
      ref="yourCoursesGrid" 
      @course-unregistered="updateAllCourses" 
    />
    <!-- Grid for all available courses -->
    <CoursesCardGrid 
      v-show="userID" 
      :userID="userID" 
      ref="coursesCardGrid" 
      @course-registered="updateYourCourses" 
    />
  </div>
</template>
  
<script>
import Nav from '../components/Nav.vue';
import YourCoursesCardGrid from '../components/YourCoursesCardGrid.vue';
import CoursesCardGrid from '../components/CoursesCardGrid.vue';

export default {
  components: {
    Nav, // Navigation bar component
    YourCoursesCardGrid, // Component for displaying the user's courses
    CoursesCardGrid // Component for displaying all available courses
  },
  data() {
    return {
      username: null, // Username of the logged-in user
      userID: null // User ID of the logged-in user
    };
  },
  mounted() {
    // Retrieve the username and user ID from local storage when the component is mounted
    this.username = localStorage.getItem('username') || ''; 
    this.userID = localStorage.getItem('userID') || '';
  },
  methods: {
    /**
     * Update the user's courses grid by fetching the latest data.
     */
    updateYourCourses() {
      this.$refs.yourCoursesGrid.fetchCourses(this.username);
    },
    /**
     * Update the grid for all courses by fetching the latest data.
     */
    updateAllCourses() {
      this.$refs.coursesCardGrid.fetchCourses();
    }
  }
};
</script>

<style>
#navbar {
  position: sticky; /* Keep the navbar fixed at the top */
  top: 0; /* Align it to the top of the page */
}
</style>