<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TrackUserLocation
{
    /**
     * Record the logged-in user's current IP and, if it changed, resolve an
     * approximate city/country from it — shown to admins in the support
     * chat panel as the user's "current location".
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $this->updateLocation(Auth::user(), $request->ip());
        }

        return $next($request);
    }

    protected function updateLocation($user, $ip)
    {
        if (!$ip || $ip === $user->last_ip_address) {
            return;
        }

        $user->last_ip_address = $ip;
        $user->last_location = $this->resolveLocation($ip);
        $user->save();
    }

    /**
     * @param  string  $ip
     * @return string|null
     */
    protected function resolveLocation($ip)
    {
        // Private/loopback IPs (localhost, LAN) can't be geolocated.
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'Local network';
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,city,regionName,country',
            ]);

            if ($response->successful() && $response->json('status') === 'success') {
                return collect([
                    $response->json('city'),
                    $response->json('regionName'),
                    $response->json('country'),
                ])->filter()->implode(', ');
            }
        } catch (\Throwable $e) {
            // Lookup failed (timeout, offline, rate-limited) — leave location as-is rather than break the request.
        }

        return null;
    }
}
