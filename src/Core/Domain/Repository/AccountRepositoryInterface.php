<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Account;

interface AccountRepositoryInterface
{
  public function save(Account $account): void;
  public function findById(string $id): ?Account;
  public function reset(): void;
}
