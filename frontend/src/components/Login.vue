<script>
import { ref, onMounted } from 'vue'

export default {
  setup() {
    const name = ref('')
    const el = ref()
    const submitForm = () => {
      console.log(`Form submitted! Name = ${name.value}`)
    }

    onMounted(() => {
      el.value.focus()
    })

    return {
      name,
      submitForm,
      el,
    }
  }
}
</script>

<template>
  <div>
    <h1>Login</h1>
    <input ref="el" type="text" placeholder="Username" v-model="name" />
    <div>
      <button @click="submitForm" v-bind:disabled="name.length == 0">
        Submit
      </button>
    </div>
  </div>
</template>

<style>
input[type='text'] {
  padding: 5px;
}

button {
  margin-top: 20px;
}
</style>