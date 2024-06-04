<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// send email function using phpmailer library

if (!function_exists('sendEmail')) {
    # code...
    function sendEmail($mailConfig)
    {
        require 'PHPMailer/src/Exceprion.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
    }
}
