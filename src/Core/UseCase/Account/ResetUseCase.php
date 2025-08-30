<?php

namespace Core\UseCase\Account;

use Core\Domain\Repository\AccountRepositoryInterface;

class ResetUseCase
{
  public function __construct(private AccountRepositoryInterface $accountRepository) {}

  public function execute(): void
  {
    $this->accountRepository->reset();
  }
}
