<?php

namespace Core\UseCase\Account;

use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\DTO\Account\WithdrawInputDTO;
use Core\Domain\Exception\AccountNotFoundException;

class WithdrawUseCase
{
  public function __construct(private AccountRepositoryInterface $accountRepository) {}

  public function execute(WithdrawInputDTO $withdrawtInputDTO): void
  {
    $account = $this->accountRepository->findById($withdrawtInputDTO->accountId);

    if (!$account) {
      throw new AccountNotFoundException();
    }

    $account->withdraw($withdrawtInputDTO->amount);
    $this->accountRepository->save($account);
  }
}
