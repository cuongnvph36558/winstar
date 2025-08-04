<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordChangeMail;

class EmailService
{
    /**
     * Detect email provider from email address
     */
    public function detectEmailProvider($email)
    {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        
        switch ($domain) {
            case 'gmail.com':
                return 'gmail';
            case 'outlook.com':
            case 'hotmail.com':
            case 'live.com':
                return 'outlook';
            case 'yahoo.com':
            case 'yahoo.co.uk':
            case 'yahoo.fr':
                return 'yahoo';
            default:
                return 'smtp'; // Use default SMTP
        }
    }

    /**
     * Send email verification with automatic provider detection
     */
    public function sendVerificationEmail($email, $verificationCode, $userName)
    {
        try {
            // Detect email provider for logging
            $provider = $this->detectEmailProvider($email);
            
            // Send email using default mailer (log driver for testing)
            Mail::to($email)->send(new EmailVerificationMail($verificationCode, $userName));
            
            Log::info("Verification email sent successfully to {$email} (detected provider: {$provider})");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send verification email to {$email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get SMTP settings for different providers
     */
    public function getSmtpSettings($provider)
    {
        $settings = [
            'gmail' => [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
            ],
            'outlook' => [
                'host' => 'smtp-mail.outlook.com',
                'port' => 587,
                'encryption' => 'tls',
            ],
            'yahoo' => [
                'host' => 'smtp.mail.yahoo.com',
                'port' => 587,
                'encryption' => 'tls',
            ],
            'smtp' => [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]
        ];

        return $settings[$provider] ?? $settings['smtp'];
    }

    /**
     * Send password change notification email
     */
    public function sendPasswordChangeEmail($email, $userName)
    {
        try {
            // Detect email provider for logging
            $provider = $this->detectEmailProvider($email);
            
            // Send email using default mailer
            Mail::to($email)->send(new PasswordChangeMail($userName));
            
            Log::info("Password change email sent successfully to {$email} (detected provider: {$provider})");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send password change email to {$email}: " . $e->getMessage());
            return false;
        }
    }
} 