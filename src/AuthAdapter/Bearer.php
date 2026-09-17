<?php

namespace Paynl\LaravelCashier\AuthAdapter;

use PayNL\Sdk\AuthAdapter\AdapterInterface;
use PayNL\Sdk\Exception\InvalidArgumentException;

class Bearer implements AdapterInterface
{
    protected string $username = '';

    protected string $token = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->token;
    }

    public function setPassword(string $password)
    {
        if ($password === '') {
            throw new InvalidArgumentException('Given bearer token can not be empty');
        }

        $this->token = $password;

        return $this;
    }

    public function getHeaderString(): string
    {
        return 'Bearer '.$this->token;
    }
}
