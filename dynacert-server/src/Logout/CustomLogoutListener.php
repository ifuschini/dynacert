<?php 
namespace App\Logout;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class MyLogoutSuccessHandler
{
    /**
     * @param LogoutEvent $logoutEvent
     * @return JsonResponse
     */
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent)
    {
        
        return new JsonResponse(["success" => true]);
    }
}