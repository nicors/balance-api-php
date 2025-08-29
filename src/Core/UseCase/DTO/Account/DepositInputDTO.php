<?php

namespace Core\UseCase\DTO\Account;

class DepositInputDTO
{
  public function __construct(
    public string $accountId,
    public float $amount
  ) {
  }
}