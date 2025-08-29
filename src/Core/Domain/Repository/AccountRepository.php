<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Account;

class AccountRepository implements AccountRepositoryInterface
{
  private array $accounts;

  public function __construct()
  {
    $this->accounts = [];
  }

  public function save(Account $account): void
  {
    $this->accounts[$account->id] = $account;
  }

  public function findById(string $id): ?Account
  {
    return $this->accounts[$id] ?? null;
  }
}