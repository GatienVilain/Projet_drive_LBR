<?php

namespace Application\Model;

class Email
{
    private string $address;
    private string $subject;
    private string $content;


    public function __construct($address, $subject, $message)
    {
        $this->address = $address;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function SendEmail()
    {
        $headers  = "Content-Type: text/html; charset=utf-8\r\n";
        $headers .= "From: totolvroum@gmail.com\r\n";
        require("components/Tools/template_mail.php");

        if ( ! mail ( $this->address, $this->subject, $content, $headers ) )
        {
            throw new \Exception("Le mail n’a pas pu être envoyé");
        }

    }
}