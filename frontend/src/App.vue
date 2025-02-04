<template>
  <div v-for="user in users" :key="user.id">
    <h2>{{ user.userID }} {{ user.username }}</h2>
  </div>
</template>

<script>
import api from './services/api'; // Ensure this is your Axios instance

export default {
  data() {
    return {
      users: []
    };
  },
  mounted() {
    api.get('/users') // Corrected syntax
      .then((response) => {
        this.users = response.data;
      })
      .catch((error) => {
        console.error('Error fetching users:', error);
      });
  }
};
</script>
