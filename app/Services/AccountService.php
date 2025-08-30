<?php

namespace App\Services;

use Core\UseCase\Account\DepositUseCase;
use Core\UseCase\Account\GetBalanceUseCase;
use Core\UseCase\Account\ResetUseCase;
use Core\UseCase\Account\TransferUseCase;
use Core\UseCase\Account\WithdrawUseCase;
use Core\UseCase\DTO\Account\DepositInputDTO;
use Core\UseCase\DTO\Account\TransferInputDTO;
use Core\UseCase\DTO\Account\WithdrawInputDTO;

class AccountService
{
  public function __construct(
    private GetBalanceUseCase $getBalanceUseCase,
    private DepositUseCase $depositUseCase,
    private WithdrawUseCase $withdrawUseCase,
    private TransferUseCase $transferUseCase,
    private ResetUseCase $resetUseCase
  ) {}

  public function getBalance(string $accountId)
  {
    return $this->getBalanceUseCase->execute($accountId);
  }

  public function reset()
  {
    $this->resetUseCase->execute();
  }

  public function handleEvent(array $eventData): array
  {
    $eventType = $eventData['type'];

    switch ($eventType) {
      case 'deposit':
        $inputDTO = new DepositInputDTO(
          accountId: $eventData['destination'],
          amount: $eventData['amount']
        );
        $this->depositUseCase->execute($inputDTO);

        return [
          'destination' => [
            'id' => $eventData['destination'],
            'balance' => $this->getBalanceUseCase->execute($eventData['destination'])
          ]
        ];
      case 'withdraw':
        $inputDTO = new WithdrawInputDTO(
          accountId: $eventData['origin'],
          amount: $eventData['amount']
        );
        $this->withdrawUseCase->execute($inputDTO);

        return [
          'origin' => [
            'id' => $eventData['origin'],
            'balance' => $this->getBalanceUseCase->execute($eventData['origin'])
          ]
        ];
      case 'transfer':
        $inputDTO = new TransferInputDTO(
          fromAccountId: $eventData['origin'],
          toAccountId: $eventData['destination'],
          amount: $eventData['amount']
        );
        $this->transferUseCase->execute($inputDTO);

        return [
          'origin' => [
            'id' => $eventData['origin'],
            'balance' => $this->getBalanceUseCase->execute($eventData['origin'])
          ],
          'destination' => [
            'id' => $eventData['destination'],
            'balance' => $this->getBalanceUseCase->execute($eventData['destination'])
          ]
        ];
      default:
        throw new \InvalidArgumentException("Invalid event type");
    }
  }
}
