<template>
  <nav class="navbar navbar-expand-lg p-0">
    <div class="container-fluid position-relative">
      <p id="title">MinimalTech</p>
      <button v-if="$route.path === '/admin'" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div :class="{'collapse navbar-collapse': $route.path === '/admin', 'navbar-collapse': $route.path !== '/admin'}" id="navbarSupportedContent">
        <ul v-if="$route.path === '/admin'" class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <button class="nav-link btn" :class="{ active: activeTab === 'yourCourses' }" id="nav-item1" @click="handleTabClick('yourCourses')">Your Courses</button>
          </li>
          <li class="nav-item">
            <button class="nav-link btn" :class="{ active: activeTab === 'userManagement' }" id="nav-item2" @click="handleTabClick('userManagement')">User Management</button>
          </li>
          <li class="nav-item">
            <button class="nav-link btn" :class="{ active: activeTab === 'courseManagement' }" id="nav-item3" @click="handleTabClick('courseManagement')">Course Management</button>
          </li>
          <li class="nav-item">
            <button class="nav-link btn" :class="{ active: activeTab === 'assignmentManagement' }" id="nav-item4" @click="handleTabClick('assignmentManagement')">Assignment Management</button>
          </li>
        </ul>
        <button v-if="$route.path === '/admin'" class="nav-link btn" id="logout" @click="logout()">Log Out</button>
      </div>
      <button v-if="$route.path !== '/admin'" class="btn logout-top-right" id="logout" @click="logout()">Log Out</button>
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
    handleTabClick(tab) {
      this.setActiveTab(tab);
      this.collapseNavbar();
    },
    collapseNavbar() {
      const navbar = document.getElementById('navbarSupportedContent');
      if (navbar.classList.contains('show')) {
        const bsCollapse = new bootstrap.Collapse(navbar, {
          toggle: true
        });
        bsCollapse.hide();
      }
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
    padding: 10px;
  }

  #logout:hover {
    background-color: #4b1f57;
    color: white;
    border-radius: 4px;
  }

  .logout-top-right {
    position: absolute;
    top: 10px;
    right: 10px;
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
      margin-bottom: 5px;
    }
    #logout {
      padding-top: 5px;
      padding-bottom: 5px;
    }
  }
</style>