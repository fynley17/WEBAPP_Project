<template>
  <header>
    <Nav />
  </header>
  <div>
    <h2>Staff Dashboard</h2>
    <CardGrid />
  </div>
</template>
  
<script>
  import Nav from '../components/Nav.vue';
  import CardGrid from '../components/CardGrid.vue';

  export default {
    components: {
      Nav,
      CardGrid
    }
  };
</script>
  