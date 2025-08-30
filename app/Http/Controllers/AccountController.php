<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Core\Domain\Exception\AccountNotFoundException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AccountController extends BaseController
{
  public function __construct(
    private AccountService $accountService
  ) {}

  public function balance(Request $request)
  {
    try {
      $accountId = $request->query('account_id');
      $balance = $this->accountService->getBalance($accountId);

      return response()->json($balance, 200);
    } catch (AccountNotFoundException $e) {
      return response()->json(0, 404);
    }
  }

  public function reset()
  {
    $this->accountService->reset();
    return response('OK', 200);
  }

  public function event(Request $request)
  {
    try {

      $eventData = $request->all();
      $result = $this->accountService->handleEvent($eventData); 
      return response()->json($result, 201);
    } catch (AccountNotFoundException $e) {
      return response(0, 404);
    } catch (\InvalidArgumentException $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Internal Server Error'], 500);
    }
  }
}
