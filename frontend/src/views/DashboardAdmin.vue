<template>
  <div id="navbar">
    <!-- Navigation bar component -->
    <Nav @change-tab="selectedTab = $event" />
  </div>
  <div class="container fluid" id="dashboard">
    <!-- Display "Your Courses" and "All Courses" grids when the "yourCourses" tab is selected -->
    <template v-if="selectedTab === 'yourCourses'">
      <!-- Grid for the user's courses -->
      <YourCoursesCardGrid 
        :username="username" 
        ref="yourCoursesGrid" 
        @course-unregistered="updateAllCourses" 
      />
      <!-- Grid for all available courses -->
      <CoursesCardGrid 
        v-if="userID" 
        :userID="userID" 
        ref="allCoursesGrid" 
        @course-registered="updateYourCourses" 
      />
    </template>

    <!-- Display User Management when the "userManagement" tab is selected -->
    <template v-else-if="selectedTab === 'userManagement'">
      <UserManagement />
    </template>

    <!-- Display Course Management when the "courseManagement" tab is selected -->
    <template v-else-if="selectedTab === 'courseManagement'">
      <CourseManagement />
    </template>

    <!-- Display Assignment Management when the "assignmentManagement" tab is selected -->
    <template v-else-if="selectedTab === 'assignmentManagement'">
      <AssignmentManagement />
    </template>
  </div>
</template>

<script>
import Nav from '../components/Nav.vue';
import YourCoursesCardGrid from '../components/YourCoursesCardGrid.vue';
import CoursesCardGrid from '../components/CoursesCardGrid.vue';
import UserManagement from '../components/UserManagement.vue';
import CourseManagement from '../components/CourseManagement.vue';
import AssignmentManagement from '../components/AssignmentManagement.vue';

export default {
  components: {
    Nav, // Navigation bar component
    YourCoursesCardGrid, // Component for displaying the user's courses
    CoursesCardGrid, // Component for displaying all available courses
    UserManagement, // Component for managing users
    CourseManagement, // Component for managing courses
    AssignmentManagement // Component for managing assignments
  },
  data() {
    return {
      username: null, // Username of the logged-in user
      userID: null, // User ID of the logged-in user
      selectedTab: 'yourCourses' // Default to the "Your Courses" tab
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
      this.$refs.allCoursesGrid.fetchCourses();
    }
  }
};
</script>

<style>
/* Styling for the navigation bar */
#navbar {
  position: sticky; /* Keep the navbar fixed at the top */
  top: 0; /* Align it to the top of the page */
  z-index: 1000; /* Ensure it appears above other elements */
}
</style>