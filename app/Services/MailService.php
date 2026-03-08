<?php

namespace App\Services;

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

/**
 * MailService
 *
 * Handles outgoing emails through Mailtrap.
 */
class MailService
{
    private $client;
    private $fromEmail;
    private $fromName;
    private $enabled;

    public function __construct()
    {
        $apiKey = $this->env('MAILTRAP_API_KEY', '');
        $useSandbox = filter_var($this->env('MAILTRAP_USE_SANDBOX', 'true'), FILTER_VALIDATE_BOOLEAN);
        $inboxId = $this->env('MAILTRAP_INBOX_ID');

        $this->fromEmail = $this->env('MAIL_FROM', 'noreply@simpleblog.local');
        $this->fromName = $this->env('MAIL_FROM_NAME', 'Simple Blog System');
        $this->enabled = !empty($apiKey);

        if (!$this->enabled) {
            return;
        }

        $this->client = MailtrapClient::initSendingEmails(
            apiKey: $apiKey,
            isSandbox: $useSandbox,
            inboxId: $useSandbox ? $inboxId : null
        );
    }

    /**
     * Send password reset email.
     */
    public function sendPasswordResetEmail($toEmail, $toName, $resetUrl)
    {
        $subject = 'Reset your password';
        $textBody = "Hello {$toName},\n\n"
            . "We received a request to reset your password.\n"
            . "Use this link to set a new password:\n{$resetUrl}\n\n"
            . "If the link is not clickable, copy and paste this URL into your browser:\n{$resetUrl}\n\n"
            . "If you did not request this, you can ignore this email.";

        $htmlBody = '<p>Hello ' . htmlspecialchars($toName, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>We received a request to reset your password.</p>'
            . '<p><a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Reset your password</a></p>'
            . '<p>If the button does not work, copy and paste this URL:</p>'
            . '<p><code>' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '</code></p>'
            . '<p>If you did not request this, you can ignore this email.</p>';

        return $this->send($toEmail, $toName, $subject, $textBody, $htmlBody);
    }

    /**
     * Send email verification email.
     */
    public function sendEmailVerificationEmail($toEmail, $toName, $verificationUrl)
    {
        $subject = 'Verify your email address';
        $textBody = "Hello {$toName},\n\n"
            . "Thanks for registering.\n"
            . "Please verify your email by using this link:\n{$verificationUrl}\n\n"
            . "If the link is not clickable, copy and paste this URL into your browser:\n{$verificationUrl}\n\n"
            . "If you did not create this account, you can ignore this email.";

        $htmlBody = '<p>Hello ' . htmlspecialchars($toName, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Thanks for registering.</p>'
            . '<p><a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '">Verify your email</a></p>'
            . '<p>If the button does not work, copy and paste this URL:</p>'
            . '<p><code>' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '</code></p>'
            . '<p>If you did not create this account, you can ignore this email.</p>';

        return $this->send($toEmail, $toName, $subject, $textBody, $htmlBody);
    }

    /**
     * Send a plain+HTML email.
     */
    public function send($toEmail, $toName, $subject, $textBody, $htmlBody = null)
    {
        if (!$this->enabled || !$this->client) {
            error_log('MailService disabled: MAILTRAP_API_KEY is missing');
            return false;
        }

        try {
            $email = (new MailtrapEmail())
                ->from(new Address($this->fromEmail, $this->fromName))
                ->to(new Address($toEmail, $toName))
                ->subject($subject)
                ->text($textBody);

            if (!empty($htmlBody)) {
                $email->html($htmlBody);
            }

            $this->client->send($email);
            return true;
        } catch (\Throwable $e) {
            error_log('Failed to send email via Mailtrap: ' . $e->getMessage());
            return false;
        }
    }

    private function env($key, $default = null)
    {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return $value;
        }

        return $default;
    }
}
