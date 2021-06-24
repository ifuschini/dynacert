<?php
// src/Controller/SecurityController.php
namespace App\Controller;

// ...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $user = $this->getUser();
        $session=new Session();
        if ($user->getUserIdentifier() == $parametersAsArray['username']) {
            $session->set('login','true');
        }


        return $this->json([
            // The getUserIdentifier() method was introduced in Symfony 5.3.
            // In previous versions it was called getUsername()
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            //'request' => $parametersAsArray,
        ]);
    }
   /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
   /**
     * @Route("/checksession", name="check_session", methods={"GET"})
     */
    public function checksession(Request $request): Response
    {
        $session=new Session();
        $response=false;
        if (($session->get('login')) && $session->get('login')=='true') {
            $response=true;
        } 
        return $this->json([
            // The getUserIdentifier() method was introduced in Symfony 5.3.
            // In previous versions it was called getUsername()
            'islogged' => $response
        ]);
    }
}
