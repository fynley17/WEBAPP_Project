<template>
  <header>
    <Nav />
  </header>
  <div>
    <h2>Staff Dashboard</h2>
    <YourCoursesCardGrid :username="username"/>
    <CoursesCardGird v-if="userID" :userID="userID" @course-registered="updateYourCourses"/>
  </div>
</template>
  
<script>
import Nav from '../components/Nav.vue';
import YourCoursesCardGrid from '../components/YourCoursesCardGrid.vue';
import CoursesCardGird from '../components/CoursesCardGird.vue';

export default {
  components: {
    Nav,
    YourCoursesCardGrid,
    CoursesCardGird
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
    console.log("userID: ", this.userID);
    this.$nextTick(() => {
      console.log("userID after nextTick:", this.userID);
    });
  },
  methods: {
    updateYourCourses() {
      this.$refs.YourCoursesCardGrid.fetchCourses(this.username);
    }
  }
};
</script>
