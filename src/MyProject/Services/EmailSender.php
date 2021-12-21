<?php

namespace MyProject\Services;
use MyProject\Models\Users\User;

class EmailSender
{
    public static function send
    (
        User $sender,
        string $subject,
        string $templateName,
        array $templatesVars = []
    ): void
    {
        extract($templatesVars);
        ob_start();
        require __DIR__ . '../../../../templates/' . $templateName;
        $body = ob_get_contents();
        ob_end_clean();
        mail($sender->getEmail(), $subject, $body, 'Content-Type: html/txt; charset=utf8');
        var_dump($body);
    }

}