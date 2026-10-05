<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

/**
 * Sends a branded OTP email for verification.
 * 
 * @param string $recipientEmail
 * @param string $recipientName
 * @param string $otpCode
 * @param string $purpose (e.g. 'registration', 'login', 'reset')
 * @return array ['success' => bool, 'message' => string, 'dev_otp' => ?string]
 */
function sendOtpEmail($recipientEmail, $recipientName, $otpCode, $purpose = 'registration') {
    $config = require __DIR__ . '/mail_config.php';

    $subject = 'Your Verification Code - Roommate & PG Finder';
    $purposeText = 'verifying your new account';

    if ($purpose === 'login') {
        $purposeText = 'logging in to your account';
    } elseif ($purpose === 'reset') {
        $purposeText = 'resetting your password';
    }

    $htmlBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Verification OTP</title>
        <style>
            body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f8ff; margin: 0; padding: 20px; }
            .email-card { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
            .header { background: linear-gradient(135deg, #2563EB, #1d4ed8); padding: 28px 20px; text-align: center; color: white; }
            .header h1 { margin: 0; font-size: 24px; letter-spacing: 0.5px; font-weight: 700; color: #ffffff; }
            .header p { margin: 6px 0 0; font-size: 14px; opacity: 0.9; }
            .content { padding: 32px 28px; color: #334155; line-height: 1.6; }
            .greeting { font-size: 17px; font-weight: 600; color: #1e293b; margin-bottom: 12px; }
            .otp-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 10px; padding: 18px; text-align: center; margin: 24px 0; }
            .otp-code { font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #2563EB; font-family: 'Courier New', Courier, monospace; }
            .otp-info { font-size: 13px; color: #64748b; margin-top: 8px; }
            .notice { font-size: 13px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 24px; }
            .footer { background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8; }
        </style>
    </head>
    <body>
        <div class='email-card'>
            <div class='header'>
                <h1>Roommate & PG Finder</h1>
                <p>Secure Account Verification</p>
            </div>
            <div class='content'>
                <div class='greeting'>Hello " . htmlspecialchars($recipientName) . ",</div>
                <p>Thank you for choosing Roommate & PG Finder. Please use the One-Time Password (OTP) below to complete {$purposeText}:</p>
                <div class='otp-box'>
                    <div class='otp-code'>{$otpCode}</div>
                    <div class='otp-info'>Valid for 10 minutes</div>
                </div>
                <p>If you did not request this verification code, please disregard this email.</p>
                <div class='notice'>
                    Never share your OTP with anyone. Our support team will never ask for your verification code.
                </div>
            </div>
            <div class='footer'>
                © " . date('Y') . " Roommate & PG Finder. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";

    $altBody = "Hello {$recipientName},\n\nYour OTP for {$purposeText} is: {$otpCode}\n\nThis OTP is valid for 10 minutes.\n\nRoommate & PG Finder";

    // Check if SMTP is configured
    if ($config['smtp_user'] === 'your-email@gmail.com' || empty($config['smtp_user']) || $config['smtp_pass'] === 'your-16-char-app-password' || empty($config['smtp_pass'])) {
        return [
            'success' => false,
            'message' => 'SMTP credentials not configured. Please add your email and 16-character App Password in includes/mail_config.php.'
        ];
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = $config['smtp_auth'];
        $mail->Username   = $config['smtp_user'];
        $mail->Password   = $config['smtp_pass'];

        if ($config['smtp_secure'] === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($config['smtp_secure'] === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->Port       = $config['smtp_port'];
        $mail->CharSet    = 'UTF-8';

        // Sender & Recipient
        $fromEmail = !empty($config['smtp_user']) && filter_var($config['smtp_user'], FILTER_VALIDATE_EMAIL) ? $config['smtp_user'] : $config['from_email'];
        $mail->setFrom($fromEmail, $config['from_name']);
        $mail->addAddress($recipientEmail, $recipientName);

        if (!empty($config['reply_to'])) {
            $mail->addReplyTo($config['reply_to'], $config['from_name']);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $altBody;

        $mail->send();

        return [
            'success' => true,
            'message' => 'OTP sent successfully to your email.'
        ];
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);

        return [
            'success' => false,
            'message' => 'Could not send verification email. Error: ' . $mail->ErrorInfo
        ];
    }
}
