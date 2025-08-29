<?php

namespace Core\UseCase\DTO\Account;

class WithdrawInputDTO
{
  public function __construct(
    public string $accountId,
    public float $amount
  ) {
  }
}