<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Actions that should be audited
     */
    protected array $auditableActions = [
        'store', 'create', 'update', 'edit', 'destroy', 'delete',
        'assign', 'revoke', 'toggle', 'activate', 'deactivate'
    ];

    /**
     * Routes that should be audited
     */
    protected array $auditableRoutes = [
        'users.*', 'roles.*', 'permissions.*', 'organizations.*', 'admin.*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only audit after the request is processed
        if ($this->shouldAudit($request, $response)) {
            $this->logAuditTrail($request, $response);
        }

        return $response;
    }

    /**
     * Determine if the request should be audited.
     */
    protected function shouldAudit(Request $request, Response $response): bool
    {
        // Don't audit GET requests (read operations)
        if ($request->isMethod('GET')) {
            return false;
        }

        // Don't audit failed requests (4xx, 5xx)
        if ($response->getStatusCode() >= 400) {
            return false;
        }

        // Check if route should be audited
        $routeName = $request->route()?->getName();
        if (!$routeName) {
            return false;
        }

        foreach ($this->auditableRoutes as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the audit trail.
     */
    protected function logAuditTrail(Request $request, Response $response): void
    {
        $user = $request->user();
        $routeName = $request->route()?->getName();
        $action = $request->route()?->getActionMethod();

        $auditData = [
            'event' => 'user_action',
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'user_roles' => $user?->roles->pluck('name')->toArray() ?? [],
            'action' => $action,
            'route' => $routeName,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
            'request_data' => $this->sanitizeRequestData($request),
            'route_parameters' => $request->route()?->parameters() ?? [],
            'response_status' => $response->getStatusCode(),
        ];

        // Add organization context if available
        if ($user && $user->organizations->isNotEmpty()) {
            $auditData['user_organizations'] = $user->organizations->pluck('name', 'id')->toArray();
        }

        // Add affected resource information
        $this->addResourceInfo($request, $auditData);

        // Log to dedicated audit channel
        Log::channel('audit')->info('RBAC Action Audit', $auditData);

        // For critical actions, also log to security channel
        if ($this->isCriticalAction($action, $routeName)) {
            Log::channel('security')->warning('Critical RBAC Action', $auditData);
        }
    }

    /**
     * Sanitize request data to remove sensitive information.
     */
    protected function sanitizeRequestData(Request $request): array
    {
        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
        
        // Remove any keys that might contain sensitive data
        $sensitiveKeys = ['secret', 'key', 'token', 'auth'];
        
        foreach ($sensitiveKeys as $sensitive) {
            $data = array_filter($data, function ($key) use ($sensitive) {
                return stripos($key, $sensitive) === false;
            }, ARRAY_FILTER_USE_KEY);
        }

        return $data;
    }

    /**
     * Add resource information to audit data.
     */
    protected function addResourceInfo(Request $request, array &$auditData): void
    {
        $routeParameters = $request->route()?->parameters() ?? [];
        
        foreach ($routeParameters as $key => $value) {
            if (is_object($value) && method_exists($value, 'getKey')) {
                $auditData["affected_{$key}_id"] = $value->getKey();
                
                // Add name/title if available
                if (isset($value->name)) {
                    $auditData["affected_{$key}_name"] = $value->name;
                } elseif (isset($value->title)) {
                    $auditData["affected_{$key}_title"] = $value->title;
                } elseif (isset($value->email)) {
                    $auditData["affected_{$key}_email"] = $value->email;
                }
            }
        }
    }

    /**
     * Determine if this is a critical action that needs special attention.
     */
    protected function isCriticalAction(string $action, string $routeName): bool
    {
        $criticalActions = ['destroy', 'delete'];
        $criticalRoutes = ['roles.destroy', 'permissions.destroy', 'admin.users.destroy'];

        return in_array($action, $criticalActions) || in_array($routeName, $criticalRoutes);
    }
}
