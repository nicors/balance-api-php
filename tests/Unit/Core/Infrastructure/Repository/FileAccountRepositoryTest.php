<?php

namespace Tests\Unit\Core\Infrastructure\Repository;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Account;
use Core\Infrastructure\Repository\FileAccountRepository;

class FileAccountRepositoryTest extends TestCase
{
  private string $testFilePath;

  protected function setUp(): void
  {
    $this->testFilePath = __DIR__ . '/var/www/data/test_accounts.json';
    @unlink($this->testFilePath);
  }

  protected function tearDown(): void
  {
    @unlink($this->testFilePath);
  }

  /**
   * @test
   */
  public function itShouldBeAbleToSaveAndFindAnAccount(): void
  {
    $repository = new FileAccountRepository($this->testFilePath);
    $account = new Account('123', 100);
    $repository->save($account);

    $fetchedAccount = $repository->findById('123');
    $this->assertNotNull($fetchedAccount);
    $this->assertEquals('123', $fetchedAccount->id);
    $this->assertEquals(100, $fetchedAccount->balance);
    $this->assertEquals($account, $fetchedAccount);
  }

  /**
   * @test
   */
  public function itShouldReturnNullWhenAccountNotFound(): void
  {
    $repository = new FileAccountRepository($this->testFilePath);
    $fetchedAccount = $repository->findById('nonexistent');
    $this->assertNull($fetchedAccount);
  }

  /**
   * @test
   */
  public function testReset(): void
  {
    $repository = new FileAccountRepository($this->testFilePath);
    $account = new Account('123', 100);
    $repository->save($account);

    $repository->reset();
    $fetchedAccount = $repository->findById('123');
    $this->assertNull($fetchedAccount);
  }
}
