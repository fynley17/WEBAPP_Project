<template>
  <div class="container mt-4">
    <h2 class="mb-2">Course Management</h2>
    <p>Click on the course to show people attending</p>

    <!-- Search input -->
    <div class="mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Search by title, date, or description"
        v-model="searchQuery"
        @input="filterCourses"
      />
    </div>

    <button class="btn" id="add" @click="addCourse()">Add Course</button>

    <!-- Upcoming Courses Table -->
    <h3>Upcoming Courses</h3>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <!-- Sortable columns for upcoming courses -->
            <th @click="sortBy('courseID', 'upcoming')" class="sortable">
              # <span v-if="sortKey === 'courseID' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cTitle', 'upcoming')" class="sortable">
              Title <span v-if="sortKey === 'cTitle' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cDate', 'upcoming')" class="sortable date-column">
              Date <span v-if="sortKey === 'cDate' && sortTarget === 'upcoming'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th>Duration</th>
            <th>Max Attendance</th>
            <th>Current Attendance</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <template v-for="course in filteredUpcomingCourses" :key="course.courseID">
            <!-- Main Row -->
            <tr @click="toggleRow(course.courseID)">
              <td>{{ course.courseID }}</td>
              <td>{{ course.cTitle }}</td>
              <td class="date-column">{{ course.cDate }}</td>
              <td>{{ course.cDuration }}</td>
              <td>{{ course.maxAttendees }}</td>
              <td>{{ course.currentAttendence }}</td>
              <td>{{ course.cDescription }}</td>
              <td>
                <!-- Edit and delete buttons for upcoming courses -->
                <button class="btn" id="action" @click.stop="editCourse(course)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn" id="action" @click.stop="deleteCourse(course.courseID)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>

            <!-- Expandable Row -->
            <tr v-if="expandedRows.includes(course.courseID)" class="expandable-row">
              <td colspan="8">
                <div class="expanded-content">
                  <h5>Enrolled Users</h5>
                  <table class="custom-table">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in course.assignedUsers" :key="user.userID">
                        <td>{{ user.userID }}</td>
                        <td>{{ user.username }}</td>
                        <td>
                          <!-- Delete button for enrolled users -->
                          <button class="btn" id="action" @click.stop="deleteUser(user.assignmentID)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="course.assignedUsers.length === 0">
                        <td colspan="3">No users assigned</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Past Courses Table -->
    <h3>Past Courses</h3>
    <div class="table-responsive">
      <table class="custom-table">
        <thead id="table-header">
          <tr>
            <!-- Sortable columns for past courses -->
            <th @click="sortBy('courseID', 'past')" class="sortable">
              #<span v-if="sortKey === 'courseID' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cTitle', 'past')" class="sortable">
              Title <span v-if="sortKey === 'cTitle' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th @click="sortBy('cDate', 'past')" class="sortable date-column">
              Date <span v-if="sortKey === 'cDate' && sortTarget === 'past'" :class="sortOrder === 1 ? 'asc' : 'desc'"></span>
            </th>
            <th>Duration</th>
            <th>Max Attendance</th>
            <th>Current Attendance</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <template v-for="course in filteredPastCourses" :key="course.courseID">
            <!-- Main Row -->
            <tr @click="toggleRow(course.courseID)">
              <td>{{ course.courseID }}</td>
              <td>{{ course.cTitle }}</td>
              <td class="date-column">{{ course.cDate }}</td>
              <td>{{ course.cDuration }}</td>
              <td>{{ course.maxAttendees }}</td>
              <td>{{ course.currentAttendence }}</td>
              <td>{{ course.cDescription }}</td>
            </tr>

            <!-- Expandable Row -->
            <tr v-if="expandedRows.includes(course.courseID)" class="expandable-row">
              <td colspan="7">
                <div class="expanded-content">
                  <h5>Enrolled Users</h5>
                  <table class="custom-table">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Username</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in course.assignedUsers" :key="user.userID">
                        <td>{{ user.userID }}</td>
                        <td>{{ user.username }}</td>
                      </tr>
                      <tr v-if="course.assignedUsers.length === 0">
                        <td colspan="2">No users assigned</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <Modal 
      :showModal="showModal" 
      @update:showModal="showModal = $event" 
      :selectedCourse="selectedCourse"
      :isAddingCourse="isAddingCourse" 
      @update:isAddingCourse="isAddingCourse = $event"
      :isEditingCourse="isEditingCourse" 
      @update:isEditingCourse="isEditingCourse = $event"
      :isAddingUser="isAddingUser" 
      @update:isAddingUser="isAddingUser = $event"
      :selectedUser="selectedUser"
      :isEditingUser="isEditingUser" 
      @update:isEditingUser="isEditingUser = $event"
      :message="modalMessage"
      @course-added="fetchCourses()">
    </Modal>
  </div>
</template>

<script>
import api from '../services/api';
import Modal from './Modal.vue';

export default {
  name: "CourseManagement",
  data() {
    return {
      courses: [], // List of all courses
      filteredUpcomingCourses: [], // Filtered list of upcoming courses
      filteredPastCourses: [], // Filtered list of past courses
      searchQuery: "", // Search query for filtering
      sortKey: "", // Key to sort by
      sortOrder: 1, // Sort order: 1 for ascending, -1 for descending
      sortTarget: "upcoming", // Target table for sorting ('upcoming' or 'past')
      showModal: false,
      modalMessage: '',
      selectedCourse: null,
      isAddingCourse: false,
      isEditingCourse: false,
      selectedUser: null,
      isAddingUser: false,
      isEditingUser: false,
      expandedRows: [] // Track expanded rows
    };
  },
  mounted() {
    this.fetchCourses();
  },
  methods: {
    async fetchCourses() {
      try {
        const response = await api.get(`/courses`);
        this.courses = Array.isArray(response.data) ? response.data : [response.data];
        this.filterCourses(); // Filter courses into upcoming and past
      } catch (error) {
        console.error('Error fetching courses:', error);
      }
    },
    addCourse() {
      this.isAddingCourse = true; 
      this.selectedCourse = null;
      this.showModal = true;
    },
    editCourse(course) {
      this.isEditingCourse = true;
      this.selectedCourse = course;
      this.showModal = true;
    },
    async deleteCourse(courseID) {
      try {
        const response = await api.delete(`/courses/${courseID}`);
        console.log('Course deleted:', response.data);
        this.fetchCourses(); // Refresh the course list after deletion
      } catch (error) {
        console.error('Error deleting course:', error);
      }
    },
    /**
     * Filter courses into upcoming and past based on the current date.
     */
    filterCourses() {
      const today = new Date();
      const query = this.searchQuery.toLowerCase();

      this.filteredUpcomingCourses = this.courses.filter((course) => {
        const courseDate = new Date(course.cDate);
        return (
          courseDate >= today &&
          (course.cTitle.toLowerCase().includes(query) ||
            course.cDate.includes(query) ||
            course.cDescription.toLowerCase().includes(query))
        );
      });

      this.filteredPastCourses = this.courses.filter((course) => {
        const courseDate = new Date(course.cDate);
        return (
          courseDate < today &&
          (course.cTitle.toLowerCase().includes(query) ||
            course.cDate.includes(query) ||
            course.cDescription.toLowerCase().includes(query))
        );
      });
    },
    /**
     * Sort courses by a given key for a specific table.
     * @param {string} key - The key to sort by (e.g., 'cTitle', 'cDate').
     * @param {string} target - The target table to sort ('upcoming' or 'past').
     */
    sortBy(key, target) {
      if (this.sortKey === key && this.sortTarget === target) {
        // If already sorting by this key in the same table, toggle the sort order
        this.sortOrder *= -1;
      } else {
        // Otherwise, set the new sort key and target, and default to ascending order
        this.sortKey = key;
        this.sortTarget = target;
        this.sortOrder = 1;
      }

      const targetArray = target === "upcoming" ? this.filteredUpcomingCourses : this.filteredPastCourses;

      targetArray.sort((a, b) => {
        if (a[key] < b[key]) return -1 * this.sortOrder;
        if (a[key] > b[key]) return 1 * this.sortOrder;
        return 0;
      });
    },
    async toggleRow(courseID) {
      const index = this.expandedRows.indexOf(courseID);
      if (index === -1) {
        // Expand the row
        this.expandedRows.push(courseID);

        // Find the course and fetch users
        const course = this.courses.find((c) => c.courseID === courseID);
        if (course && !course.assignedUsers) {
          course.assignedUsers = []; // Initialize the array to avoid reactivity issues
          await this.fetchAssignedUsers(course);
        }
      } else {
        // Collapse the row
        this.expandedRows.splice(index, 1);
      }
    },
    async fetchAssignedUsers(course) {
      try {
        const response = await api.get(`/assignedUsers/${course.courseID}`);
        course.assignedUsers = response.data;
      } catch (error) {
        console.error(`Error fetching users for course ${course.courseID}:`, error);
      }
    },
  },
  components: {
    Modal
  }
};
</script>

<style scoped>
.container {
  background-color: #302835;
  padding: 20px;
  border-radius: 8px;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  border-radius: 10px;
  overflow: hidden;
}

.custom-table th, .custom-table td {
  border: 1px solid #2C272E;
  padding-left: 8px;
  padding-right: 8px;
  text-align: center; /* Center align text in table cells */
}

.custom-table th {
  background-color: #2C272E;
  color: white;
}

.custom-table tbody tr {
  background-color: #4b3f52;
}

.custom-table tbody tr:hover {
  background-color: #3e3444;
  transition: 0.3s;
}

.date-column {
  white-space: nowrap; /* Prevent wrapping of date */
}

#action {
  color: #E59934;
  border: none;
  background: none;
  cursor: pointer;
}

#action:hover {
  background-color: #493111;
}

#add {
  background-color: #753188;
  color: white;
  border-radius: 5px;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}

#add:hover {
  background-color: #5a2566;
}

.expandable-row .expanded-content {
  padding: 10px;
  background-color: #2C272E;
  border-radius: 8px;
}

.expandable-row .custom-table {
  margin-top: 10px;
}

/* Add styles for sortable headers */
.sortable {
  cursor: pointer;
  position: relative;
}

.sortable::after {
  content: "⇅"; /* Default indicator for sortable columns */
  font-size: 0.8rem;
  margin-left: 5px;
  color: #ccc;
}

.sortable.asc::after {
  content: "↑"; /* Ascending order indicator */
  color: #000;
}

.sortable.desc::after {
  content: "↓"; /* Descending order indicator */
  color: #000;
}
</style>