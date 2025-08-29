<?php

namespace Core\Domain\Exception;

use Exception;

class AccountNotFoundException extends Exception
{
  public function __construct($message = "Account not found", $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}