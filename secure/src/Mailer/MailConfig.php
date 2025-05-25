<?php

namespace src\Mailer;

class MailConfig
{
    /**
     * Retrieve SMTP configuration from environment variables.
     *
     * @return array The SMTP configuration settings.
     */
    public static function getSMTPConfig(): array
    {
        return [
            'host' => $_ENV["MAIL_HOST"],
            'port' => $_ENV["MAIL_PORT"],
            'username' => $_ENV["MAIL_USERNAME"],
            'password' => $_ENV["MAIL_PASSWORD"],
            'encryption' => $_ENV["MAIL_ENCRYPTION"],
        ];
    }

    /**
     * Retrieve the "from" email address and name.
     *
     * @return array The "from" email address and name.
     */
    public static function getFromAddress(): array
    {
        return [
            'address' => $_ENV["MAIL_FROM_ADDRESS"],
            'name' => $_ENV["MAIL_FROM_NAME"]
        ];
    }

    /**
     * Retrieve the default reply-to address and name.
     *
     * @return array The reply-to email address and name.
     */
    public static function getReplyToAddress(): array
    {
        return [
            'address' => $_ENV["MAIL_REPLY_TO_ADDRESS"],
            'name' => $_ENV["MAIL_REPLY_TO_NAME"]
        ];
    }
}
