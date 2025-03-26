<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <p id="title">MinimalTech</p>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul v-if="$route.path === '/admin'" class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <button class="nav-link btn" :class="{ active: activeTab === 'yourCourses' }" id="nav-item1" @click="setActiveTab('yourCourses')">Your Courses</button>
            </li>
            <li class="nav-item">
              <button class="nav-link btn" :class="{ active: activeTab === 'userManagement' }" id="nav-item2" @click="setActiveTab('userManagement')">User Management</button>
            </li>
            <li class="nav-item">
              <button class="nav-link btn" :class="{ active: activeTab === 'courseManagement' }" id="nav-item3" @click="setActiveTab('courseManagement')">Course Management</button>
            </li>
            <li class="nav-item">
              <button class="nav-link btn" :class="{ active: activeTab === 'assignmentManagement' }" id="nav-item4" @click="setActiveTab('assignmentManagement')">Assignment Management</button>
            </li>
          </ul>
          <button class="btn" id="logout" @click="logout()">Log Out</button>
        </div>
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeTab: 'yourCourses' // Default active tab
      };
    },
    methods: {
      setActiveTab(tab) {
        this.activeTab = tab;
        this.$emit('change-tab', tab);
      },
      logout() {
        localStorage.removeItem('token');
        this.$router.push('/');
      }
    }
  };
  </script>
  
<style>
  #title {
    font-size: x-large;
    font-weight: bolder;
    margin-top: 10px;
    margin-right: 10px;
  }

  .nav-link.btn {
    color: #000000;
  }

  .nav-link.btn.active{
    background-color: #facf41;
    color: black;
    border-radius: 4px;
  }

  #logout {
    color: white;
    border-radius: 5px;
    background-color: #4e2a84;
  }

  nav {
    font-family: Sofia Pro;
  }
</style>