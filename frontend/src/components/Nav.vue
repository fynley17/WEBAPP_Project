<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Vortech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <form class="d-flex me-2 mb-2 mb-lg-0" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search course" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <button class="d-flex btn btn-outline-danger" @click="logout">Log Out</button>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        methods: {
            logout() {
                localStorage.removeItem('token');
                this.$router.push('/');
            }
        }
    }
</script>