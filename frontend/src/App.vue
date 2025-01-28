<!-- <script setup>
import Login from './components/LoginForm.vue'
import axios from 'axios'
</script>

<template>
</template> -->
<template>
  <div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  mounted() {
    axios
      .get('http://localhost/api/users')
  }
}
</script>