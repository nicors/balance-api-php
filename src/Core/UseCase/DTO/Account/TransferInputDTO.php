<?php

namespace Core\UseCase\DTO\Account;

class TransferInputDTO
{
  public function __construct(
    public string $fromAccountId,
    public string $toAccountId,
    public float $amount
  ) {
  }
}