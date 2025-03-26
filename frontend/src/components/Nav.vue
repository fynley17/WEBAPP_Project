<template>
    <nav class="navbar navbar-expand-lg p-0">
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
    color: white;
    font-size: x-large;
    font-weight: bolder;
    margin-top: 10px;
    margin-right: 10px;
  }

  .nav-link.btn {
    color: White;
  }

  .nav-link.btn:hover {
    background-color: #E59934;
    color: white;
    border-radius: 4px;
  }

  .nav-link.btn.active{
    background-color: #E59934;
    color: white;
    border-radius: 4px;
  }

  #logout {
    color: white;
    border-radius: 5px;
    background-color: #753188;
  }

  nav {
    font-family: Sofia Pro;
    background: #2C272E;
  }

  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
  }

  @media screen and (max-width: 768px) {
    .nav-link.btn{
      padding: 5px;
    }
    
  }
</style>