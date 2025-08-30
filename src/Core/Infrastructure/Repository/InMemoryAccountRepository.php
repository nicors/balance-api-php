<?php

namespace Core\Infrastructure\Repository;

use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Entity\Account;

class InMemoryAccountRepository implements AccountRepositoryInterface
{
  private static $accounts = [];

  public function save(Account $account): void
  {
    self::$accounts[$account->id] = $account;
  }

  public function findById(string $id): ?Account
  {
    return self::$accounts[$id] ?? null;
  }

  public function reset(): void
  {
    self::$accounts = [];
  }
}