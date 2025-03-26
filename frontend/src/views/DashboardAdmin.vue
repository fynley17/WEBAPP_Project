<template>
  <header>
    <Nav @change-tab="selectedTab = $event" />
  </header>
    <div class="container fluid" id="dashboard">
      <template v-if="selectedTab === 'yourCourses'">
        <YourCoursesCardGrid :username="username" ref="yourCoursesGrid" @course-unregistered="updateAllCourses" />
        <CoursesCardGrid v-if="userID" :userID="userID" ref="allCoursesGrid" @course-registered="updateYourCourses" />
      </template>

      <template v-else-if="selectedTab === 'userManagement'">
        <UserManagement />
      </template>

      <template v-else-if="selectedTab === 'courseManagement'">
        <CourseManagement />
      </template>

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
    Nav,
    YourCoursesCardGrid,
    CoursesCardGrid,
    UserManagement,
    CourseManagement,
    AssignmentManagement
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
    },
    updateAllCourses() {
      this.$refs.allCoursesGrid.fetchCourses();
    }
  }
};
</script>