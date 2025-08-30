<?php

namespace Core\UseCase\Account;

use Core\Domain\Exception\AccountNotFoundException;
use Core\Domain\Repository\AccountRepositoryInterface;

class GetBalanceUseCase
{
  public function __construct(private AccountRepositoryInterface $accountRepository) {}

  public function execute(string $accountId): int
  {
    $account = $this->accountRepository->findById($accountId);
    if (!$account) {
      throw new AccountNotFoundException();
    }
    return $account->balance;
  }
}
