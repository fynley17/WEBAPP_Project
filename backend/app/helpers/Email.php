<?php

namespace App\Helpers;

use App\Models\Course;
use App\Models\User;

class Email
{

    // Sends an enrolment confirmation email to the user
    public static function Enroll($userID, $courseID)
    {
        // Retrieve user details using their ID
        $user = User::findByID($userID);

        // Retrieve course details using its ID
        $course = Course::findByID($courseID);
        $courseName = $course['cTitle'];
        $courseDate = $course['cDate'];

        // User's email address
        $to = $user['email'];

        // Subject of the email
        $subject = "Course enrolment";

        // Message content for the enrolment notification
        $message = "You have been enrolled in: {$courseName}\nThis will occur on {$courseDate}.";

        // Attempt to send the email and return a response
        if (mail($to, $subject, $message)) {
            return json_encode("Email sent successfully!");
        } else {
            return json_encode("Failed to send email.");
        }
    }

    // Sends a password reset email to the user
    public static function Reset($email, $resetUrl)
    {
        // Recipient's email address
        $to = $email;

        // Subject of the email
        $subject = 'Password Reset Link';

        // Message content including the reset URL
        $message = "Click the link below to reset your password:\n{$resetUrl}";

        // Attempt to send the email and return a response
        if (mail($to, $subject, $message)) {
            return json_encode("Email sent successfully!");
        } else {
            return json_encode("Failed to send email.");
        }
    }
}
