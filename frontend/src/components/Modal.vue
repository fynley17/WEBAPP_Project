<template>
  <div 
    class="modal fade" 
    tabindex="-1" 
    :class="{ 'show': showModal }" 
    :style="{ display: showModal ? 'block' : 'none' }" 
    aria-hidden="!showModal"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ modalTitle }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <!-- Check if selectedCourse is null -->
          <div v-if="viewCourse">
            <p><strong>Date:</strong> {{ courseFormData.cDate }}</p>
            <p><strong>Duration:</strong> {{ courseFormData.cDuration }} days</p>
            <p><strong>Attendees:</strong> {{ courseFormData.currentAttendence }} / {{ courseFormData.maxAttendees }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ courseFormData.cDescription }}</p>
          </div>

          <div v-else-if="isAddingCourse">
            <h3 class="text-center">Add New Course</h3>
            <form @submit.prevent="submitCourseForm">
              <div class="mb-3">
                  <label for="title" class="form-label">Course Title</label>
                  <input type="text" class="form-control" id="title" v-model="courseFormData.cTitle" required>
              </div>
              <div class="row mb-3">
                  <div class="col">
                      <label for="date" class="form-label">Date</label>
                      <input type="date" class="form-control" id="date" v-model="courseFormData.cDate">
                  </div>
                  <div class="col">
                      <label for="duration" class="form-label">Duration</label>
                      <input type="number" class="form-control" id="duration" v-model="courseFormData.cDuration" min="0">
                  </div>
              </div>
              <div class="mb-3">
                  <label for="attendees" class="form-label">Maximum Attendees</label>
                  <input type="number" class="form-control" id="attendees" v-model="courseFormData.maxAttendees" min="0">
              </div>
              <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" id="description" v-model="courseFormData.cDescription" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-secondary w-100">Submit</button>
            </form>
          </div>

          <div v-else-if="isEditingCourse">
            <h3 class="text-center">Edit Course</h3>
            <form @submit.prevent="submitEditCourseForm">
              <div class="mb-3">
                  <label for="title" class="form-label">Course Title</label>
                  <input type="text" class="form-control" id="title" v-model="courseFormData.cTitle" required>
              </div>
              <div class="row mb-3">
                  <div class="col">
                      <label for="date" class="form-label">Date</label>
                      <input type="date" class="form-control" id="date" v-model="courseFormData.cDate">
                  </div>
                  <div class="col">
                      <label for="duration" class="form-label">Duration</label>
                      <input type="number" class="form-control" id="duration" v-model="courseFormData.cDuration" min="0">
                  </div>
              </div>
              <div class="mb-3">
                  <label for="attendees" class="form-label">Maximum Attendees</label>
                  <input type="number" class="form-control" id="attendees" v-model="courseFormData.maxAttendees" min="0">
              </div>
              <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" id="description" v-model="courseFormData.cDescription" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-secondary w-100">Submit</button>
            </form>
          </div>

          <!-- Check if creating a user -->
          <div v-else-if="isAddingUser">
              <h3 class="text-center">Create Account</h3>
              <form @submit.prevent="submitForm">
                <div class="mb-3 d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Firstname" v-model="formData.firstName">
                    <input type="text" class="form-control" placeholder="Lastname" v-model="formData.lastName">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email" v-model="formData.email">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Username" v-model="formData.username">
                </div>
                <div class="mb-3 position-relative">
                    <input 
                        :type="isPasswordVisible ? 'text' : 'password'" 
                        class="form-control" 
                        placeholder="Password" 
                        v-model="formData.password"
                    >
                    <button type="button" @click="togglePasswordVisibility" class="password-toggle">
                        <i :class="isPasswordVisible ? 'bi bi-eye-slash' : 'bi bi-eye'" style="color: grey;"></i>
                    </button>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Job title" v-model="formData.jobTitle">
                </div>
                <div class="mb-3">
                  <select class="form-select" v-model="formData.accessLevel">
                    <option selected disabled value="">Access level</option>
                    <option value="admin">admin</option>
                    <option value="staff">staff</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Submit</button>
              </form>
          </div>
          <!-- Check if editing a user -->
          <div v-else-if="isEditingUser">
              <h3 class="text-center">Edit Account</h3>
              <form @submit.prevent="submitEditForm">
                <div class="mb-3 d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Firstname" v-model="formData.firstName">
                    <input type="text" class="form-control" placeholder="Lastname" v-model="formData.lastName">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email" v-model="formData.email">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Username" v-model="formData.username">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Job title" v-model="formData.jobTitle">
                </div>
                <div class="mb-3">
                  <select class="form-select" v-model="formData.accessLevel">
                    <option selected disabled value="">Access level</option>
                    <option value="admin">admin</option>
                    <option value="staff">staff</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Save Changes</button>
              </form>
          </div>
          <!-- If selectedCourse is null, show the delete message -->
          <div v-else>
            <p>{{ message }}</p> <!-- Show the message instead of course details -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  props: {
    showModal: Boolean,
    message: String,
    viewCourse: Boolean,
    isAddingCourse: Boolean,
    isEditingCourse: Boolean,
    selectedCourse: Object,
    isAddingUser: Boolean,
    isEditingUser: Boolean,
    selectedUser: Object
  },
  computed: {
    modalTitle() {
      if (this.isEditingCourse) {
        return "Edit Course"
      }else if (this.isAddingCourse) {
        return "Add Course";
      } else if (this.selectedCourse) {
        return this.selectedCourse.cTitle;
      } else if (this.isAddingUser) {
        return 'Create Account'; 
      } else if (this.isEditingUser) {
        return 'Edit Account'; 
      }
      return 'Action Status';
    }
  },
  data() {
    return {
      formData: {
        firstName: '',
        lastName: '',
        email: '',
        username: '',
        jobTitle: '',
        accessLevel: ''
      },
      courseFormData: {
        cTitle: '',
        cDate: '',
        cDuration: '',
        maxAttendees: '',
        cDescription: ''
      },
      isPasswordVisible: false 
    };
  },
  watch: {
    selectedUser: {
      handler(newUser) {
        if (newUser) {
          this.formData = { ...newUser };
        }
      },
      immediate: true
    },
    selectedCourse: {
      handler(newCourse) {
        if (newCourse) {
          this.courseFormData = { ...newCourse }; // This will copy the selected course details into courseFormData
        }
      },
      immediate: true // This ensures it runs immediately when the component is created or selectedCourse changes
    }
  },
  methods: {
    togglePasswordVisibility() {
      this.isPasswordVisible = !this.isPasswordVisible;
    },
    async submitForm() {
      console.log('Form Data Before Submit:', this.formData);
      if (!this.formData.email || !this.formData.username || !this.formData.password) {
        console.error('Missing required fields');
        return;
      }
      try {
        await api.post("/users", this.formData);
        console.log('User added successfully');
 ;
        this.closeModal();
        this.$emit('user-added');
      } catch (error) {
        console.error('Error adding user:', error);
      }
    },
    async submitEditForm() {
      console.log('Form Data Before Edit Submit:', this.formData);
      if (!this.formData.email || !this.formData.username) {
        console.error('Missing required fields');
        return;
      }
      try {
        await api.patch(`/users/${this.selectedUser.userID}`, this.formData);
        console.log('User updated successfully');

        this.closeModal();
        this.$emit('user-added');
      } catch (error) {
        console.error('Error updating user:', error);
      }
    },
    closeModal() {
      this.$emit('update:showModal', false);
      this.$emit('update:isAddingUser', false);
      this.$emit('update:isEditingUser', false);
      this.$emit('update:isAddingCourse', false);
      this.$emit('update:isEditingCourse', false);
      this.formData = {
        firstName: '',
        lastName: '',
        email: '',
        username: '',
        jobTitle: '',
        accessLevel: ''
      };
      this.courseFormData = {
        cTitle: '',
        cDate: '',
        cDuration: '',
        maxAttendees: '',
        cDescription: ''
      };
    },
    async submitCourseForm() {
      console.log(this.courseFormData)
      try {
        await api.post("/courses", this.courseFormData);
        this.closeModal();
        this.$emit("course-added");
      } catch (error) {
        console.error("Error adding course:", error);
      }
    },
    async submitEditCourseForm() {
      console.log(this.courseFormData)
      try {
        await api.patch(`/courses/${this.selectedCourse.courseID}`, this.courseFormData);
        this.closeModal();
        this.$emit("course-added");
      } catch (error) {
        console.error("Error adding course:", error);
      }
    }
  }
}
</script>

<style scoped>
.modal-backdrop {
  z-index: 1040 !important;
}
.modal {
  display: none;
}
.modal.show {
  display: block;
}
.password-toggle {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
}
</style>
