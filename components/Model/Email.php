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
        $this->content = $message;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function SendEmail()
    {
        $headers  = "Content-Type: text/plain; charset=utf-8\r\n";
        $headers .= "From: totolvroum@gmail.com\r\n";

        if ( ! mail ( $this->address, $this->subject, $this->content, $headers ) )
        {
            throw new \Exception("Le mail n’a pas pu être envoyé");
        }

    }
}