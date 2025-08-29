<?php

namespace Tests\Unit\Core\UseCase\Account;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;
use Core\Domain\Exception\AccountNotFoundException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\Account\TransferUseCase;
use Core\UseCase\DTO\Account\WithdrawInputDTO;
use Core\UseCase\Account\WithdrawUseCase;
use Core\UseCase\DTO\Account\TransferInputDTO;

class TransferUseCaseUnitTest extends TestCase
{
  /**
   * @test
   */
  public function itShouldBeAbleToTransferBetweenTwoExistingAccounts()
  {
    $accountFrom = new Account(
      id: '123',
      balance: 200.0
    );

    $accountTo = new Account(
      id: '456',
      balance: 100.0
    );

    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')
      ->willReturnMap([
        ['123', $accountFrom],
        ['456', $accountTo],
      ]);
    $accountRepository->expects(self::exactly(2))->method('save');

    $transferInputDTO = new TransferInputDTO(
      fromAccountId: '123',
      toAccountId: '456',
      amount: 50.0
    );

    $transferUseCase = new TransferUseCase($accountRepository);
    $transferUseCase->execute($transferInputDTO);

    $this->assertEquals(150.0, $accountFrom->balance);
    $this->assertEquals(150.0, $accountTo->balance);
  }

  /**
   * @test
   */
  public function itShouldThrowAnErrorWhenTheFromAccountDoesNotExist()
  {
    $this->expectException(AccountNotFoundException::class);
    $this->expectExceptionMessage("Account not found");

    $accountTo = new Account(
      id: '456',
      balance: 100.0
    );

    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->method('findById')
      ->willReturnMap([
        ['123', null],
        ['456', $accountTo],
      ]);
    $accountRepository->expects(self::never())->method('save');

    $transferInputDTO = new TransferInputDTO(
      fromAccountId: '123',
      toAccountId: '456',
      amount: 50.0
    );

    $transferUseCase = new TransferUseCase($accountRepository);
    $transferUseCase->execute($transferInputDTO);
  }
}
