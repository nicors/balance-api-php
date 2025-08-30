<?php

namespace App\Providers;

use Core\Domain\Repository\AccountRepositoryInterface;
use Core\UseCase\Account\GetBalanceUseCase;
use App\Services\AccountService;
use Core\Infrastructure\Repository\FileAccountRepository;
use Core\UseCase\Account\DepositUseCase;
use Core\UseCase\Account\ResetUseCase;
use Core\UseCase\Account\TransferUseCase;
use Core\UseCase\Account\WithdrawUseCase;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(AccountRepositoryInterface::class, function ($app) {
      return new FileAccountRepository();
    });

    $this->app->singleton(GetBalanceUseCase::class, function ($app) {
      return new GetBalanceUseCase($app->make(AccountRepositoryInterface::class));
    });

    $this->app->singleton(ResetUseCase::class, function ($app) {
      return new ResetUseCase($app->make(AccountRepositoryInterface::class));
    });

    $this->app->singleton(DepositUseCase::class, function ($app) {
      return new DepositUseCase($app->make(AccountRepositoryInterface::class));
    });

    $this->app->singleton(WithdrawUseCase::class, function ($app) {
      return new WithdrawUseCase($app->make(AccountRepositoryInterface::class));
    });
  
    $this->app->singleton(TransferUseCase::class, function ($app) {
      return new TransferUseCase($app->make(AccountRepositoryInterface::class));
    });

    $this->app->singleton(AccountService::class, function ($app) {
      return new AccountService(
        $app->make(GetBalanceUseCase::class),
        $app->make(DepositUseCase::class),
        $app->make(WithdrawUseCase::class),
        $app->make(TransferUseCase::class),
        $app->make(ResetUseCase::class)
      );
    });
  }
}
