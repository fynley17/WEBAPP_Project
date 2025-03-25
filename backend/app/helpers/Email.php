<?php
namespace App\Helpers;

class Email{
    public static function Enroll($userID,$courseID){
        $user = User::findByID($userID);
        $course = Course::findByID($courseID);
        $courseName = $course['cTitle'];
        $courseDate = $course['cDate'];

        $to = $user['email'];
        $subject = "Course enrollment";
        $message = "You have a new enrollment: ${courseName}\n This will occur on ${courseDate}";

        if (mail($to, $subject, $message)) {
            return json_encode("Email sent successfully!");
        } else {
            return json_encode("Failed to send email.");
        }
    }

    public static function Reset($email,$resetUrl){
        // Send the reset URL to the user's email
        $to = $email;
        $subject = 'Password Reset Link';
        $message = "Click the link below to reset your password:\n$resetUrl";

        if (mail($to, $subject, $message)) {
            return json_encode("Email sent successfully!");
        } else {
            return json_encode("Failed to send email.");
        }
    }
}