<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EnsureUserHasOrganizationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $user = $request->user();
        
        // Users with admin permissions can access all organizations
        if ($user && $user->can('manage_all_organizations')) {
            return $next($request);
        }

        // If there's an organization parameter in the route
        $organization = $request->route('organization');
        
        if ($organization) {
            // Check if user belongs to this organization
            if (!$user || !$organization->hasMember($user)) {
                abort(403, 'You do not have access to this organization.');
            }
        }

        return $next($request);
    }
}
