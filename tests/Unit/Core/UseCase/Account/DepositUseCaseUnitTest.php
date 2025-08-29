<?php

namespace Tests\Unit\Core\UseCase\Account;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\DTO\Account\DepositInputDTO;
use Core\UseCase\Account\DepositUseCase;

class DepositUseCaseUnitTest extends TestCase
{
  /**
   * @test
   */
  public function itShouldBeAbleToDepositIntoAnExistingAccount()
  {
    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')->willReturn($account);
    $accountRepository->expects(self::once())->method('save');

    $depositInputDTO = new DepositInputDTO(
      accountId: '123',
      amount: 50.0
    );

    $depositUseCase = new DepositUseCase($accountRepository);
    $depositUseCase->execute($depositInputDTO);

    $this->assertEquals(150.0, $account->balance);
  }

  /**
   * @test
   */
  public function itShouldBeAbleToDepositIntoANewAccount()
  {
    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')->willReturn(null);

    $accountRepository->expects(self::once())->method('save')->with($this->callback(function (Account $account) {
      return $account->id === '456' && $account->balance === 75.0;
    }));

    $depositInputDTO = new DepositInputDTO(
      accountId: '456',
      amount: 75.0
    );

    $depositUseCase = new DepositUseCase($accountRepository);
    $depositUseCase->execute($depositInputDTO);
  }
}
