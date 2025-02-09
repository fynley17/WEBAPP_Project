<template>
  <header>
    <Nav />
  </header>
  <div>
    <h2>Staff Dashboard</h2>
    <CardGrid :username="username"/>
  </div>
</template>
  
<script>
import Nav from '../components/Nav.vue';
import CardGrid from '../components/CardGrid.vue';

export default {
  components: {
    Nav,
    CardGrid
  },
  data() {
    return {
      username: null
    };
  },
  mounted() {
    this.username = localStorage.getItem('username'); // Corrected: Use userID
    console.log("Retrieved username:", this.username); // Corrected: Use this.userID
  }
};
</script>
