<?php
namespace App\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AccountNotVerifiedException extends CustomUserMessageAuthenticationException
{
    public function __construct()
    {
        parent::__construct('Contul tău nu este verificat. Verifică emailul pentru link-ul de confirmare.');
    }
}