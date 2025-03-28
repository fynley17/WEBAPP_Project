<template>
  <nav class="navbar navbar-expand-lg p-0">
    <div class="container-fluid position-relative">
      <!-- Website title -->
      <p id="title">MinimalTech</p>

      <!-- Navbar toggler for admin view -->
      <button 
        v-if="$route.path === '/admin'" 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarSupportedContent" 
        aria-controls="navbarSupportedContent" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar content -->
      <div 
        :class="{'collapse navbar-collapse': $route.path === '/admin', 'navbar-collapse': $route.path !== '/admin'}" 
        id="navbarSupportedContent"
      >
        <!-- Admin-specific navigation links -->
        <ul v-if="$route.path === '/admin'" class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <!-- Your Courses tab -->
            <button 
              class="nav-link btn" 
              :class="{ active: activeTab === 'yourCourses' }" 
              id="nav-item1" 
              @click="handleTabClick('yourCourses')"
            >
              Your Courses
            </button>
          </li>
          <li class="nav-item">
            <!-- User Management tab -->
            <button 
              class="nav-link btn" 
              :class="{ active: activeTab === 'userManagement' }" 
              id="nav-item2" 
              @click="handleTabClick('userManagement')"
            >
              User Management
            </button>
          </li>
          <li class="nav-item">
            <!-- Course Management tab -->
            <button 
              class="nav-link btn" 
              :class="{ active: activeTab === 'courseManagement' }" 
              id="nav-item3" 
              @click="handleTabClick('courseManagement')"
            >
              Course Management
            </button>
          </li>
          <li class="nav-item">
            <!-- Assignment Management tab -->
            <button 
              class="nav-link btn" 
              :class="{ active: activeTab === 'assignmentManagement' }" 
              id="nav-item4" 
              @click="handleTabClick('assignmentManagement')"
            >
              Assignment Management
            </button>
          </li>
        </ul>

        <!-- Logout button for admin view -->
        <button 
          v-if="$route.path === '/admin'" 
          class="nav-link btn" 
          id="logout" 
          @click="logout()"
        >
          Log Out
        </button>
      </div>

      <!-- Logout button for non-admin view -->
      <button 
        v-if="$route.path !== '/admin'" 
        class="btn logout-top-right" 
        id="logout" 
        @click="logout()"
      >
        Log Out
      </button>
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
    /**
     * Set the active tab and emit an event to notify the parent component.
     * @param {string} tab - The tab to set as active.
     */
    setActiveTab(tab) {
      this.activeTab = tab;
      this.$emit('change-tab', tab);
    },
    /**
     * Handle tab click events and collapse the navbar if necessary.
     * @param {string} tab - The tab that was clicked.
     */
    handleTabClick(tab) {
      this.setActiveTab(tab);
      this.collapseNavbar();
    },
    /**
     * Collapse the navbar if it is expanded (for mobile view).
     */
    collapseNavbar() {
      const navbar = document.getElementById('navbarSupportedContent');
      if (navbar.classList.contains('show')) {
        const bsCollapse = new bootstrap.Collapse(navbar, {
          toggle: true
        });
        bsCollapse.hide();
      }
    },
    /**
     * Log the user out by removing the token and redirecting to the login page.
     */
    logout() {
      localStorage.removeItem('token'); // Remove the authentication token
      this.$router.push('/'); // Redirect to the login page
    }
  }
};
</script>

<style>
  /* Styling for the website title */
  #title {
    color: white;
    font-size: x-large;
    font-weight: bolder;
    margin-top: 10px;
    margin-right: 10px;
  }

  /* Styling for navigation links */
  .nav-link.btn {
    color: white;
  }

  .nav-link.btn:hover {
    background-color: #E59934;
    color: white;
    border-radius: 4px;
  }

  .nav-link.btn.active {
    background-color: #E59934;
    color: white;
    border-radius: 4px;
  }

  /* Styling for the logout button */
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

  /* Positioning for the logout button in non-admin view */
  .logout-top-right {
    position: absolute;
    top: 10px;
    right: 10px;
  }

  /* Navbar styling */
  nav {
    font-family: Sofia Pro;
    background: #2C272E;
  }

  /* Custom styling for the navbar toggler icon */
  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
  }

  /* Responsive design for smaller screens */
  @media screen and (max-width: 768px) {
    .nav-link.btn {
      padding: 5px;
      margin-bottom: 5px;
    }
    #logout {
      padding-top: 5px;
      padding-bottom: 5px;
    }
  }
</style>