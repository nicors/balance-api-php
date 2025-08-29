<?php

namespace Tests\Unit\Core\UseCase\Account;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;
use Core\Domain\Exception\AccountNotFoundException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\DTO\Account\WithdrawInputDTO;
use Core\UseCase\Account\WithdrawUseCase;

class WithdrawUseCaseUnitTest extends TestCase
{
  /**
   * @test
   */
  public function itShouldWithdrawFromAnExistingAccount(): void
  {
    $accountId = '123';
    $initialBalance = 200.0;
    $withdrawAmount = 50.0;
    $expectedBalance = $initialBalance - $withdrawAmount;

    $account = new Account($accountId, $initialBalance);

    $accountRepositoryMock = $this->createMock(AccountRepositoryInterface::class);
    $accountRepositoryMock->method('findById')->with($accountId)->willReturn($account);
    $accountRepositoryMock->expects($this->once())->method('save')->with($this->callback(function (Account $savedAccount) use ($expectedBalance) {
      return $savedAccount->balance === $expectedBalance;
    }));

    $withdrawInputDTO = new WithdrawInputDTO($accountId, $withdrawAmount);
    $withdrawUseCase = new WithdrawUseCase($accountRepositoryMock);
    $withdrawUseCase->execute($withdrawInputDTO);
  }

  /**
   * @test
   */
  public function itShouldNotWithdrawIfInsufficientBalance(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("Insufficient balance");

    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')->willReturn($account);
    $accountRepository->expects(self::never())->method('save');
    $withdrawInputDTO = new WithdrawInputDTO(
      accountId: '123',
      amount: 150.0
    );

    $withdrawUseCase = new WithdrawUseCase($accountRepository);
    $withdrawUseCase->execute($withdrawInputDTO);
  }

  /**
   * @test
   */
  public function itShouldNotWithdrawFromNonExistingAccount(): void
  {
    $this->expectException(AccountNotFoundException::class);
    $this->expectExceptionMessage("Account not found");
    
    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')->willReturn(null);
    $accountRepository->expects(self::never())->method('save');
    
    $withdrawInputDTO = new WithdrawInputDTO(
      accountId: '999',
      amount: 50.0
    );
    
    $withdrawUseCase = new WithdrawUseCase($accountRepository);
    $withdrawUseCase->execute($withdrawInputDTO);
  }
}
