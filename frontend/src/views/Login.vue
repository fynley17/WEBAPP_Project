<template>
    <div id="window" class="d-flex justify-content-center align-items-center">
        <div class="row mt-5">
            <div class="col">
                <!-- Login form -->
                <form class="p-5 rounded shadow-lg form-container" @submit.prevent="login" 
                    style="
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(10px);
                        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
                    ">
                    <h1 class="text-center mb-4 text-white">MinimalTech</h1>
                    <!-- Username input field -->
                    <div class="form-floating mb-4">
                        <input type="text" v-model="username" class="form-control form-control-lg" id="username" placeholder="Username" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white; border-radius: 10px;
                            ">
                        <label for="username" class="text-dark">Username</label>
                    </div>
                    <!-- Password input field -->
                    <div class="form-floating mb-4">
                        <input type="password" v-model="password" class="form-control form-control-lg" id="password" placeholder="Password" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white;
                                border-radius: 10px;
                            ">
                        <label for="password" class="text-dark">Password</label>
                    </div>
                    <!-- Sign-in button -->
                    <div class="d-flex justify-content-center align-items-center">
                        <button
                            class="btn px-5" type="submit" id="login"
                        >
                            Sign In
                        </button>
                    </div>
                    <!-- Display login messages -->
                    <p v-if="message" :class="messageClass" class="mt-3 text-white">{{ message }}</p>
                    <!-- Forgot password link -->
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
            username: "", // Username entered by the user
            password: "", // Password entered by the user
            message: "", // Message to display to the user
            messageClass: "text-danger" // Class for styling the message
        };
    },
    methods: {
        /**
         * Handle the login process.
         */
        async login() {
            try {
                const response = await api.post("/login", {
                    username: this.username,
                    password: this.password
                });

                const data = response.data;

                if (data.success) {
                    this.message = data.message; // Display success message
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
                            this.$router.push('/admin'); // Redirect to admin dashboard
                        } else {
                            this.$router.push('/staff'); // Redirect to staff dashboard
                        }
                    }, 100);
                }
            } catch (error) {
                // Handle specific error messages from the backend
                if (error.response && error.response.data && error.response.data.error) {
                    this.message = error.response.data.error; // Display backend error message
                } else {
                    this.message = "An unexpected error occurred. Please try again."; // Display generic error message
                }
                this.messageClass = "text-danger";
                console.error('Login failed', error);
            }
        },
        /**
         * Handle the forgot password process.
         */
        async forgotPassword() {
            const promptUsername = prompt("Please enter your username:"); // Prompt user for their username
            if (promptUsername) {
                try {
                    const response = await api.post("/forgot-password", { username: promptUsername });
                    this.message = response.data.message; // Display success message
                    this.messageClass = "text-success";
                } catch (error) {
                    this.message = "Failed to send password reset email. Please try again."; // Display error message
                    this.messageClass = "text-danger";
                    console.error('Forgot Password failed', error);
                }
            }
        }
    }
};
</script>

<style>
    /* Full-screen window styling */
    #window {
        height: 100vh;
        background: linear-gradient(to right, #4e2a84, #ff6a00); /* Gradient background */
    }

    /* Styling for the login form container */
    .form-container {
        width: 400px;
        max-width: 100%;
        background-color: white; /* Set the background color */
        font-family: popins;
    }

    /* Styling for the heading */
    h1 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    /* Styling for the login button */
    #login {
        background-color: white; /* Set the background color */
        color: #4e2a84; /* Set the text color */
        border: none;
        border-radius: 50px;
        font-size: large;
        font-weight: 600;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Responsive design for smaller screens */
    @media screen and (max-width: 600px) {
        .form-container {
            width: 300px; /* Adjust form width for smaller screens */
        }
        #window {
            background: #4e2a84; /* Change background color for smaller screens */
        }
    }
</style>
