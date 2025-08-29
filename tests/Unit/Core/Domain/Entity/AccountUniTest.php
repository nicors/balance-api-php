<?php

namespace Testes\Unit\Core\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;

class AccountUniTest extends TestCase
{
  /**
   * @test
   */
  public function itShouldBeCreatedWithIdAndBalance(): void
  {
    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $this->assertEquals('123', $account->id);
    $this->assertEquals(100.0, $account->balance);
  }

  /**
   * @test
   */
  public function itShouldDeposit(): void
  {
    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $account->deposit(50.0);

    $this->assertEquals(150.0, $account->balance);
  }

  /**
   * @test
   */
  public function itShouldWithdraw(): void
  {
    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $account->withdraw(30.0);

    $this->assertEquals(70.0, $account->balance);
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

    $account->withdraw(150.0);
  }
}
