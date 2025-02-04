<template>
  <div v-for="user in users" :key="user.id">
    <h2>{{ user.userID }} {{ user.username }}</h2>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      users:[]
    }
  },
  mounted() {
    axios
      .get('http://localhost/api/users')
      .then((response) => {
        this.users = response.data
      })
  }
}
</script>