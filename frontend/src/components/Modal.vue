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
          <h5 class="modal-title">Action Status</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <p>{{ message }}</p>
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
    message: String
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
