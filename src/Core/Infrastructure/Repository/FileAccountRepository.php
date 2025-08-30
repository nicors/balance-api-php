<?php

namespace Core\Infrastructure\Repository;

use Core\Domain\Entity\Account;
use Core\Domain\Repository\AccountRepositoryInterface;

class FileAccountRepository implements AccountRepositoryInterface
{
  private static array $accounts = [];
  private static bool $isLoaded = false;

  public function __construct(private string $filePath = '/var/www/data/accounts.json')
  {
    if (!self::$isLoaded) {
      $this->loadAccounts();
      self::$isLoaded = true;
    }
  }

  public function save(Account $account): void
  {
    self::$accounts[$account->id] = $account;
    $this->saveAccountsToFile(self::$accounts);
  }

  public function findById(string $id): ?Account
  {
    return self::$accounts[$id] ?? null;
  }

  public function reset(): void
  {
    self::$accounts = [];
    $this->saveAccountsToFile();
  }

  private function loadAccounts(): void
  {
    if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
      self::$accounts = [];
      return;
    }

    $json = file_get_contents($this->filePath);
    $data = json_decode($json, true);

    if (!is_array($data)) {
      self::$accounts = [];
      return;
    }

    $accounts = [];

    foreach ($data as $id => $accountData) {
      $accounts[$id] = new Account($accountData['id'], $accountData['balance']);
    }

    self::$accounts = $accounts;
  }

  private function saveAccountsToFile(): void
  {
    $formattedAccounts = [];
    foreach (self::$accounts as $account) {
      $formattedAccounts[$account->id] = $account->toArray();
    }

    $jsonData = json_encode($formattedAccounts, JSON_PRETTY_PRINT);
    file_put_contents($this->filePath, $jsonData);
  }
}
