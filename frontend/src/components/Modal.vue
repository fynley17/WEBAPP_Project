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
export default {
  props: {
    showModal: Boolean,
    message: String, // To handle delete messages or any other dynamic message
    selectedCourse: Object // Prop to handle course details
  },
  computed: {
    modalTitle() {
      // Check if selectedCourse is present and return the course title, otherwise show a generic title
      return this.selectedCourse ? this.selectedCourse.cTitle : 'Action Status';
    }
  },
  methods: {
    closeModal() {
      this.$emit('update:showModal', false); // Emit to parent to close the modal
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
