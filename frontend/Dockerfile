# Use an official Node.js image as the base image
FROM node:16

# Set working directory
WORKDIR /usr/src/app

# Install dependencies
COPY package*.json ./
RUN npm install

# Copy the rest of the frontend files
COPY . .

# Expose the port that the Vue app will run on
EXPOSE 8080
