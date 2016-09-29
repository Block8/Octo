<?php

namespace Octo\System\JobHandler;

use b8\Config;
use Octo\Job\Handler;
use Exception;

class SendEmailHandler extends Handler
{
    public static function getJobTypes()
    {
        return [
            'Octo.System.SendEmail' => 'Send Email',
        ];
    }

    protected function verifyJob()
    {
        $data = $this->job->getData();

        if (empty($data['subject'])) {
            throw new Exception('Email jobs require a subject');
        }

        if (empty($data['to']) || !is_array($data['to'])) {
            throw new Exception('Email jobs require a recipient');
        }

        if (empty($data['body'])) {
            throw new Exception('Email jobs require a body');
        }
    }

    public function run()
    {
        $data = $this->job->getData();
        $config = Config::getInstance();
        $mail = new \PHPMailer();

        // Enable SMTP if required:
        if (isset($config->site['smtp_server'])) {
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $config->get('site.smtp_server', null);
            $mail->Username = $config->get('site.smtp_username', null);
            $mail->Password = $config->get('site.smtp_password', null);
        }

        // Is this email a HTML email?
        $mail->IsHTML(false);

        if (!empty($data['html']) && $data['html']) {
            $mail->IsHTML(true);
        }

        $mail->Subject = $data['subject'];
        $mail->CharSet = "UTF-8";

        // Handle recipients and CCs:
        foreach ($data['to'] as $recipient) {
            $mail->addAddress($recipient['email'], $recipient['name']);
        }

        if (isset($data['cc']) && is_array($data['cc'])) {
            foreach ($data['cc'] as $recipient) {
                $mail->addCc($recipient['email'], $recipient['name']);
            }
        }

        // Handle Reply To:
        if (isset($data['reply_to']) && is_array($data['reply_to'])) {
            $mail->addReplyTo($data['reply_to']['email'], $data['reply_to']['name']);
        }

        // Handle From:
        if (isset($config->site['email_from'])) {
            $mail->SetFrom($config->site['email_from'], $config->site['email_from_name']);
        }

        // Handle attachments:
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            foreach ($data['attachments'] as $name => $path) {
                $mail->addAttachment($path, $name);
            }
        }

        $mail->Body = $data['body'];

        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }

        return true;
    }

}