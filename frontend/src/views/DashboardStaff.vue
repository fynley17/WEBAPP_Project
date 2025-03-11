<template>
  <header>
    <Nav />
  </header>
  <div>
    <YourCoursesCardGrid :username="username" ref="yourCoursesGrid" @course-unregistered="updateAllCourses" />
    <CoursesCardGrid v-show="userID" :userID="userID" ref="coursesCardGrid" @course-registered="updateYourCourses" />
  </div>
</template>
  
<script>
import Nav from '../components/Nav.vue';
import YourCoursesCardGrid from '../components/YourCoursesCardGrid.vue';
import CoursesCardGrid from '../components/CoursesCardGrid.vue';

export default {
  components: {
    Nav,
    YourCoursesCardGrid,
    CoursesCardGrid
  },
  data() {
    return {
      username: null,
      userID: null
    };
  },
  mounted() {
    this.username = localStorage.getItem('username') || ''; // Corrected: Use userID
    this.userID = localStorage.getItem('userID') || '';
  },
  methods: {
    updateYourCourses() {
      this.$refs.yourCoursesGrid.fetchCourses(this.username);
    },
    updateAllCourses() {
      this.$refs.coursesCardGrid.fetchCourses();
    }
  }
};
</script>
