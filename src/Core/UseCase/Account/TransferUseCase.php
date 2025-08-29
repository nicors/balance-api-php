<?php

namespace Core\UseCase\Account;

use Core\Domain\Entity\Account;
use Core\Domain\Exception\AccountNotFoundException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\DTO\Account\TransferInputDTO;

class TransferUseCase
{
  public function __construct(private AccountRepositoryInterface $accountRepository) {}

  public function execute(TransferInputDTO $transferInputDTO): void
  {
    $accountFrom = $this->transferFrom($transferInputDTO->fromAccountId, $transferInputDTO->amount);
    $accountTo = $this->transferTo($transferInputDTO->toAccountId, $transferInputDTO->amount);

    $this->accountRepository->save($accountFrom);
    $this->accountRepository->save($accountTo);
  }

  private function transferFrom($accountId, $amount): Account
  {
    $account = $this->accountRepository->findById($accountId);

    if (!$account) {
      throw new AccountNotFoundException();
    }

    $account->withdraw($amount);
    return $account;
  }

  private function transferTo($accountId, $amount): Account
  {
    $account = $this->accountRepository->findById($accountId);

    if (!$account) {
      $account = new Account($accountId, 0);
    }

    $account->deposit($amount);
    return $account;
  }
}
