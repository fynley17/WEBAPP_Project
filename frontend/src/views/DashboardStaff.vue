<template>
  <header>
    <Nav />
  </header>
  <div>
    <h2>Staff Dashboard</h2>
    <YourCoursesCardGrid :username="username"/>
    <CoursesCardGird />
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
      username: null
    };
  },
  mounted() {
    this.username = localStorage.getItem('username'); // Corrected: Use userID
    console.log("Retrieved username:", this.username); // Corrected: Use this.userID
  }
};
</script>
