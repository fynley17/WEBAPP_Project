<template>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh; background: linear-gradient(to right, #4e2a84, #ff6a00);">
        <div class="row mt-5">
            <div class="col">
                <!-- Password reset form -->
                <form class="p-5 rounded shadow-lg form-container" @submit.prevent="resetPassword" 
                    style="
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(10px);
                        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
                    ">
                    <h1 class="text-center mb-4 text-white">MinimalTech</h1>
                    <p class="text-center text-white">Enter new password</p>
                    <!-- Password input field -->
                    <div class="form-floating mb-4">
                        <input type="password" v-model="password" class="form-control form-control-lg" id="password" placeholder="Password" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white; border-radius: 10px;
                            ">
                        <label for="password" class="text-dark">Password</label>
                    </div>
                    <!-- Verify password input field -->
                    <div class="form-floating mb-4">
                        <input type="password" v-model="password2" class="form-control form-control-lg" id="password2" placeholder="Verify password" 
                            style="
                                background: rgba(255, 255, 255, 0.3);
                                color: white;
                                border-radius: 10px;
                            ">
                        <label for="password2" class="text-dark">Verify password</label>
                    </div>
                    <!-- Reset button -->
                    <div class="d-flex justify-content-center align-items-center">
                        <button
                            class="btn px5" type="submit" 
                            style="
                                background-color: white;
                                color: #4e2a84;
                                border: none;
                                text-transform: uppercase;
                                border-radius: 50px;
                                font-size: large;
                            "
                        >
                            <b class="m-4">Reset</b>
                        </button>
                    </div>
                    <!-- Display reset password messages -->
                    <p v-if="message" :class="messageClass" class="mt-3">{{ message }}</p>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../services/api';

    export default {
        props: {
            token: {
                type: String,
                required: true // Token is required for password reset
            }
        },
        data() {
            return {
                password: "", // New password entered by the user
                password2: "", // Password confirmation entered by the user
                message: "", // Message to display to the user
                messageClass: "text-danger" // Class for styling the message
            };
        },
        methods: {
            /**
             * Handle the password reset process.
             */
            async resetPassword() {
                // Check if the passwords match
                if (this.password !== this.password2) {
                    this.message = "Passwords do not match"; // Display warning message
                    this.messageClass = "text-warning";
                    return;
                }
                const token = this.$route.query.token; // Retrieve the token from the query parameters
                console.log(token);
                try {
                    const response = await api.post("/reset-password", {
                        token: token,
                        password: this.password
                    });
                    this.message = response.data.message; // Display success message
                    this.messageClass = "text-success";
                    this.$router.push('/'); // Redirect to the login page
                } catch (error) {
                    console.error("Reset Password Error:", error.response?.data || error.message);
                    if (error.response && error.response.data) {
                        console.log("Error response data:", error.response.data);
                        this.message = error.response.data.error || "An error occurred"; // Display backend error message
                    } else {
                        this.message = "Something went wrong"; // Display generic error message
                    }
                }
            }
        }
    }
</script>

<style scoped>
/* Styling for the password reset form container */
.form-container {
    width: 400px;
    max-width: 100%;
    background-color: white; /* Set the background color */
    font-family: Sofia Pro;
}
</style>