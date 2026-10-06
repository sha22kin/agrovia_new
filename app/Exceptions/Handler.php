<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * A stale/expired CSRF token (a page left open too long, the back button after a logout, a second tab)
     * would otherwise land on the branded 419 page for every ordinary form on the site. For an ajax/fetch
     * request, every page here already handles a plain error message gracefully - so it gets a small JSON
     * body instead of Laravel's HTML error page, which a fetch().then(r=>r.json()) call can't parse. For a
     * real page submit, bouncing back to the same page with that message (and the non-sensitive input still
     * in place) is far less jarring than "Oppos! PAGE EXPIRED", and lets them just press submit again.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            $message = 'Your session timed out. Please try that again.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $message], 419);
            }

            return redirect()
                ->back()
                ->withInput($request->except(array_merge(['_token'], $this->dontFlash)))
                ->with('error', $message);
        }

        return parent::render($request, $e);
    }
}
