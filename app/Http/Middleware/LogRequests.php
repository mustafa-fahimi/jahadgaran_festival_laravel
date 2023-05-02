<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $log = sprintf(
      "[%s] %s %s",
      now()->format('Y-m-d H:i:s'),
      $request->getMethod(),
      $request->getRequestUri()
    );

    file_put_contents(storage_path('logs/requests.log'), $log . PHP_EOL, FILE_APPEND);

    return $next($request);
  }
}
