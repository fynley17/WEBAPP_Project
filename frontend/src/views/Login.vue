<template>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh; background: linear-gradient(to right, #4e2a84, #ff6a00);">
        <div class="row mt-5">
            <div class="col">
                <form class="p-5 rounded shadow-lg form-container" @submit.prevent="login" 
                    style="
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(10px);
                        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
                    ">
                    <h1 class="text-center mb-4 text-white">MinimalTech</h1>
                    <div class="form-floating mb-4">
                        <input type="text" v-model="username" class="form-control form-control-lg" id="username" placeholder="Username" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white; border-radius: 10px;
                            ">
                        <label for="username" class="text-dark">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" v-model="password" class="form-control form-control-lg" id="password" placeholder="Password" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white;
                                border-radius: 10px;
                            ">
                        <label for="password" class="text-dark">Password</label>
                    </div>
                    <button
                        class="btn btn-lg form-control" type="submit" 
                        style="
                            background: linear-gradient(45deg, #8e2de2, #ff6a00);
                            color: white; border: none;
                            border-radius: 10px;
                        "
                    >
                        Login
                    </button>
                    <p v-if="message" :class="messageClass" class="mt-3 text-white">{{ message }}</p>
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
                }


                if (response.data.token) {

                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('username', response.data.username);
                    localStorage.setItem('userID', response.data.userID);
                    localStorage.setItem('user', JSON.stringify({ role: response.data.accessLevel }));

                    const userRole = response.data.accessLevel;
                    
                    setTimeout(() => {
                        if (userRole === 'admin') {
                            this.$router.push('/admin');
                        } else {
                            this.$router.push('/staff');
                        }
                    }, 100);
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
