<template>
  <div 
    class="modal fade" 
    tabindex="-1" 
    :class="{ 'show': showModal }" 
    :style="{ display: showModal ? 'block' : 'none' }" 
    aria-hidden="!showModal"
    @click.self="closeModal"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ modalTitle }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <!-- Check if selectedCourse is null -->
          <div v-if="selectedCourse">
            <p><strong>Date:</strong> {{ selectedCourse.cDate }}</p>
            <p><strong>Duration:</strong> {{ selectedCourse.cDuration }} days</p>
            <p><strong>Attendees:</strong> {{ selectedCourse.currentAttendence }} / {{ selectedCourse.maxAttendees }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ selectedCourse.cDescription }}</p>
          </div>
          <!-- check if isAddingUser is null -->
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
                    <input type="password" class="form-control" placeholder="Password" v-model="formData.password">
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
    selectedCourse: Object,
    isAddingUser: Boolean
  },
  computed: {
    modalTitle() {
      if (this.selectedCourse) {
        return this.selectedCourse.cTitle;
      } else if (this.isAddingUser) {
        return 'Create Account'; // <-- Set title when adding a user
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
        password: '',
        jobTitle: '',
        accessLevel: ''
      }
    };
  },
  methods: {
    async submitForm() {
      console.log('Form Data Before Submit:', this.formData);
      if (!this.formData.email || !this.formData.username || !this.formData.password) {
        console.error('Missing required fields');
        return;
      }
      try {
        const response = await api.post("/users", this.formData);
        console.log('User added successfully');


        // Emit an event to the parent to refresh the user list
        this.$emit('userAdded');

        // Reset form and close modal
        this.formData = {
          firstName: '',
          lastName: '',
          email: '',
          username: '',
          password: '',
          jobTitle: '',
          accessLevel: ''
        };
        this.$emit('update:showModal', false);
      } catch (error) {
        console.error('Error adding user:', error);
      }
    },
    closeModal() {
      this.$emit('update:showModal', false);
      this.$emit('update:isAddingUser', false);
    }
  }
}
</script>



<style scoped>
.modal-backdrop {
  z-index: 1040 !important;
}
.modal {
  display: none; /* Ensure modal is hidden by default */
}
.modal.show {
  display: block; /* Make sure modal is visible when showModal is true */
}
</style>
