<?php

namespace App\Hotel\Http\Middleware;

use App\Hotel\Models\Administrator;
use App\Hotel\Services\HotelService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelContextMiddleware
{
    public function __construct(
        private readonly HotelService $hotelService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Administrator|null $user */
        $user = $request->user('hotel');
        if ($user === null) {
            return redirect(route('auth.login'));
        }
        dd($user);
        $this->hotelService->setHotel($user->hotel_id);

        return $next($request);
    }
}
