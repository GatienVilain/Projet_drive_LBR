<?php

namespace Application\Model;

class Password
{
	private string $value;

	public function __construct($password)
	{
		$this->value = $password;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function checkFormat(): bool
	{
		$majuscule = preg_match('@[A-Z]@', $this->value);
		$minuscule = preg_match('@[a-z]@', $this->value);
		$chiffre = preg_match('@[0-9]@', $this->value);
		$pattern=preg_match('/[\'\/~`\!@#$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $this->value);

		if( !$majuscule || !$pattern || !$minuscule || !$chiffre || strlen($this->value) < 8 )
		{
			return false;
		}
		else
			return true;
	}
}

