<template>
    <div id="window" class="d-flex justify-content-center align-items-center">
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
                    <div class="d-flex justify-content-center align-items-center">
                        <button
                            class="btn px-5" type="submit" id="login"
                        >
                            Sign In
                        </button>
                    </div>
                    <p v-if="message" :class="messageClass" class="mt-3 text-white">{{ message }}</p>
                    <p class="mt-3 text-center">
                        <a href="#" @click.prevent="forgotPassword" class="text-white">Forgot Password?</a>
                    </p>
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

                if (data.success) {
                    this.message = data.message;
                    this.messageClass = "text-success";

                    // Store token and user data in localStorage
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('username', data.username);
                    localStorage.setItem('userID', data.userID);
                    localStorage.setItem('user', JSON.stringify({ role: data.accessLevel }));

                    // Redirect based on user role
                    const userRole = data.accessLevel;
                    setTimeout(() => {
                        if (userRole === 'admin') {
                            this.$router.push('/admin');
                        } else {
                            this.$router.push('/staff');
                        }
                    }, 100);
                }
            } catch (error) {
                // Handle specific error messages from the backend
                if (error.response && error.response.data && error.response.data.error) {
                    this.message = error.response.data.error;
                } else {
                    this.message = "An unexpected error occurred. Please try again.";
                }
                this.messageClass = "text-danger";
                console.error('Login failed', error);
            }
        },
        async forgotPassword() {
            const promptUsername = prompt("Please enter your username:");
            if (promptUsername) {
                try {
                    const response = await api.post("/forgot-password", { username: promptUsername });
                    this.message = response.data.message;
                    this.messageClass = "text-success";
                } catch (error) {
                    this.message = "Failed to send password reset email. Please try again.";
                    this.messageClass = "text-danger";
                    console.error('Forgot Password failed', error);
                }
            }
        }
    }
};
</script>

<style>
    #window {
        height: 100vh;
        background: linear-gradient(to right, #4e2a84, #ff6a00);
    }
    .form-container {
        width: 400px;
        max-width: 100%;
        background-color: white;
        font-family: popins;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    #login {
        background-color: white;
        color: #4e2a84;
        border: none;
        border-radius: 50px;
        font-size: large;
        font-weight: 600;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    @media screen and (max-width: 600px){
            .form-container {
                width: 300px;
            }
            #window {
                background: #4e2a84;
            }
        }
</style>
