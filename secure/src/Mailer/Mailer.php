<?php

namespace src\Mailer;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    protected PHPMailer $mailer;
    protected array $recipients = [];
    protected array $attachments = [];
    protected ?string $replyToMail = null;
    protected ?string $replyToName = null;
    protected ?string $fromMail = null;
    protected ?string $fromName = null;
    protected ?string $body = null;
    protected ?string $subject = null;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    /**
     * Configures the PHPMailer instance with SMTP settings.
     *
     * Retrieves SMTP configuration from the MailConfig class and sets up the PHPMailer instance
     * with the necessary SMTP parameters such as host, port, authentication, username, password, and encryption.
     *
     * @return void
     */
    private function setupMailer(): void
    {
        $config = MailConfig::getSMTPConfig();

        $this->mailer->isSMTP();
        $this->mailer->CharSet = "UTF-8";
        $this->mailer->Encoding = "base64";
        $this->mailer->Host = $config["host"];
        $this->mailer->Port = $config["port"];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config["username"];
        $this->mailer->Password = $config["password"];
        $this->mailer->SMTPSecure = $config["encryption"];
    }

    /**
     * Sets the sender's email address and name.
     *
     * @param string $email The sender's email address.
     * @param string $name The sender's name (optional).
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function setFrom(string $email, string $name = ""): self
    {
        $this->fromMail = $email;
        $this->fromName = $name;
        return $this;
    }

    /**
     * Sets the reply-to email address and name.
     *
     * @param string $email The reply-to email address.
     * @param string $name The reply-to name (optional).
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function setReplyTo(string $email, string $name = ""): self
    {
        $this->replyToMail = $email;
        $this->replyToName = $name;
        return $this;
    }

    /**
     * Adds one or more recipients to the email.
     *
     * @param string|array $to A single email address or an array of email addresses to add as recipients.
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function addRecipient(string|array $to): self
    {
        $this->recipients = array_merge($this->recipients, (array) $to);
        return $this;
    }

    /**
     * Attaches a file to the email.
     *
     * @param string $filePath The path to the file to be attached.
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function attachFile(string $filePath): self
    {
        $this->attachments[] = $filePath;
        return $this;
    }

    /**
     * Sets the HTML template for the email body.
     *
     * @param string $filePath The path to the HTML template file.
     * @param array $variables An associative array of variables to replace in the template.
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function setHtmlTemplate(string $filePath, array $variables = []): self
    {
        if (!file_exists($filePath)) {
            return $this;
        }

        $content = file_get_contents($filePath);
        foreach ($variables as $key => $value) {
            $content = str_replace("{{ $key }}", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $content);
        }

        $this->body = $content;
        return $this;
    }

    /**
     * Sets the subject of the email.
     *
     * @param string $subject The subject of the email.
     * @return self Returns the instance of the Mailer for method chaining.
     */
    public function addSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Sends the email using the configured PHPMailer instance.
     *
     * @return string Returns "Success" if the email is sent successfully, otherwise returns the exception message.
     */
    public function send(): string
    {
        try {
            $from = $this->fromMail ?? MailConfig::getFromAddress();
            $this->mailer->setFrom($from["address"], $from["name"]);

            $replyTo = $this->replyToMail ?? MailConfig::getReplyToAddress();
            if (is_array($replyTo)) {
                $this->mailer->addReplyTo($replyTo["address"], $replyTo["name"]);
            } else {
                $this->mailer->addReplyTo($this->replyToMail, $this->replyToName);
            }

            foreach ($this->recipients as $recipient) {
                $this->mailer->addAddress($recipient);
            }

            foreach ($this->attachments as $attachment) {
                $this->mailer->addAttachment($attachment);
            }

            $this->mailer->isHTML();
            $this->mailer->Subject = $this->subject ?? "No Subject";
            $this->mailer->Body = $this->body ?? "<p>This is a default email body.</p>";

            $this->mailer->send();
            return "Success";
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
