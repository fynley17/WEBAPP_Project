<template>
  <header>
    <Nav @change-tab="selectedTab = $event" />
  </header>
  
  <div class="container fluid">
    <template v-if="selectedTab === 'yourCourses'">
      <YourCoursesCardGrid :username="username" ref="yourCoursesGrid" />
      <CoursesCardGird v-if="userID" :userID="userID" @course-registered="updateYourCourses" />
    </template>

    <template v-else-if="selectedTab === 'userManagement'">
      <UserManagement />
    </template>

    <template v-else-if="selectedTab === 'courseManagement'">
      <CourseManagement />
    </template>
  </div>
</template>

<script>
import Nav from '../components/Nav.vue';
import YourCoursesCardGrid from '../components/YourCoursesCardGrid.vue';
import CoursesCardGird from '../components/CoursesCardGird.vue';
import UserManagement from '../components/UserManagement.vue';
import CourseManagement from '../components/CourseManagement.vue';

export default {
  components: {
    Nav,
    YourCoursesCardGrid,
    CoursesCardGird,
    UserManagement,
    CourseManagement
  },
  data() {
    return {
      username: null,
      userID: null,
      selectedTab: 'yourCourses' // Default to personal courses view
    };
  },
  mounted() {
    this.username = localStorage.getItem('username') || '';
    this.userID = localStorage.getItem('userID') || '';
  },
  methods: {
    updateYourCourses() {
      this.$refs.yourCoursesGrid.fetchCourses(this.username);
    }
  }
};
</script>
