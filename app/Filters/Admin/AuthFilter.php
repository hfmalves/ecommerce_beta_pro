<?php

namespace App\Filters\Admin;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user    = $session->get('user');
        $authRoutes = [
            'admin/auth/login',
            'admin/auth/register',
            'admin/auth/recovery',
            'admin/auth/reset',
        ];
        // Use the routed path: the raw URL can include index.php after rewriting.
        $path = trim($request->getUri()->getRoutePath(), '/');
        if (str_starts_with($path, 'admin/auth/')) {
            return;
        }
        if (empty($user) || empty($user['isLoggedIn'])) {
            if (! in_array($path, $authRoutes)) {
                return redirect()->to(base_url('admin/auth/login'));
            }
        }
        if (! empty($user['isLoggedIn']) && in_array($path, $authRoutes)) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        // Extra: se abrir só /admin → manda para dashboard
        if (! empty($user['isLoggedIn']) && $path === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        }
    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada por enquanto
    }
}
