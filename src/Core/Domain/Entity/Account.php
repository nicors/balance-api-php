<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;

class Account
{
  use MagicMethodsTrait;

  public function __construct(
    private string $id,
    private float $balance
  ) {
    $this->id = $id;
    $this->balance = $balance;
  }

  public function deposit(float $value): void
  {
    $this->balance += $value;
  }

  public function withdraw(float $value): void
  {
    if ($value > $this->balance) {
      throw new \InvalidArgumentException("Insufficient balance");
    }

    $this->balance -= $value;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'balance' => $this->balance,
    ];
  }
}
