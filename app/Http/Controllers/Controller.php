<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\HttpResponses;
use Exception;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests, HttpResponses;

  public function countRequests()
  {
    try {
      $path = storage_path('logs/requests.log');
      $count = count(file($path));
      return $this->success(
        $count,
        message: '',
      );
    } catch (Exception $e) {
      return $this->error(
        null,
        message: $e->getMessage(),
        code: $e->getCode(),
      );
    }
  }
}
