<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? response()->noContent()
            : redirect()->intended(Fortify::redirects('email-verification', route('dashboard')).'?verified=1');
    }
}
