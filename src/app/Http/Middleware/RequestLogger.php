<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestLogger
{

    private $excludes = [
        '_debugbar',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('logging.request.enable')) {
            if ($this->isWrite($request)) {
                $this->write($request);
            }
        }
        return $next($request);
    }


    private function isWrite(Request $request): bool
    {
        return !in_array($request->path(), $this->excludes, true);
    }


    public function write(Request $request): void
    {
        Log::channel('requestLog')
            ->debug($request->method(), [
                'url' => $request->fullUrl(),
                'request' => $request->all()
            ]);
    }
}
