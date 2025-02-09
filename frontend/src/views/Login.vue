<template>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="row mt-5">
            <div class="col">
                <form class="p-5 rounded border border-black shadow-lg form-container" @submit.prevent="login">
                    <h1 class="text-center mb-4">Vortech</h1>
                    <div class="form-floating mb-4">
                        <input type="text" v-model="username" class="form-control form-control-lg" id="username" placeholder="Username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" v-model="password" class="form-control form-control-lg" id="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                    <button class="btn btn-primary form-control btn-lg" type="submit">Login</button>
                    <p v-if="message" :class="messageClass" class="mt-3">{{ message }}</p>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../services/api'; 

export default {
    data() {
        return {
            username: "",
            password: "",
            message: "",
            messageClass: "text-danger"
        };
    },
    methods: {
        async login() {
            try {
                const response = await api.post("/login", {
                    username: this.username,
                    password: this.password
                });

                const data = response.data;
                this.message = data.message;

                if (data.success) {
                    this.messageClass = "text-success";
                    alert("Login successful!");
                    // Store session or redirect user here
                }


                if (response.data.token) {
                    console.log(response.data.userID)
                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('uaername', response.data.username)
                    const userRole = response.data.accessLevel;
                    if (userRole === 'admin') {
                        this.$router.push('/admin');
                    } else {
                        this.$router.push('/staff');
                    }
                    }

            } catch (error) {
                this.message = "Server error. Please try again.";
                this.messageClass = "text-danger";
                console.error('Login failed', error);
            }
        }
    }
};
</script>

<style scoped>
.form-container {
    width: 400px;
    max-width: 100%;
    background-color: white;
}
</style>
