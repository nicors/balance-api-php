<?php

namespace Core\UseCase\Account;

use Core\Domain\Entity\Account;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\DTO\Account\DepositInputDTO;

class DepositUseCase
{
  public function __construct(private AccountRepositoryInterface $accountRepository) {}

  public function execute(DepositInputDTO $depositInputDTO): void
  {
    $account = $this->accountRepository->findById($depositInputDTO->accountId);

    if (!$account) {
      $account = new Account($depositInputDTO->accountId, 0);
    }

    $account->deposit($depositInputDTO->amount);

    $this->accountRepository->save($account);
  }
}
