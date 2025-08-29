<?php

namespace Tests\Unit\Core\Domain\Repository;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;
use Core\Domain\Repository\AccountRepository;

class AccountRepositoryUnitTest extends TestCase
{
  /**
   * @test
   */
  public function itShouldBeAbleToSaveAndFindAnAccount()
  {
    $repository = new AccountRepository();

    $account = new Account(
      id: '123',
      balance: 100.0
    );

    $repository->save($account);

    $foundAccount = $repository->findById('123');

    $this->assertSame($account, $foundAccount);
  }
}
