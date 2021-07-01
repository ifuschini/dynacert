<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
     }
    /**
     * @Route("/admin/account", name="showUsers")
     */
    public function index(): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getDoctrine()->getRepository(User::class);
        return $this->json([
            'response' => $user->getUsers(),
        ]);
    }
    /**
     * @Route("/admin/account/insert/", methods="POST",name="insertNewAccount")
     */
    public function insert(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $repository = $this->getDoctrine()->getRepository(User::class);
        $entityManager = $this->getDoctrine()->getManager();
        $user=new User();
        $user->setUsername($parametersAsArray['username']);
        $user->setPassword($this->passwordHasher->hashPassword(
             $user,
             $parametersAsArray['password']
        ));
        $user->setRoles(array($parametersAsArray['role']));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([
            'response' => $repository->getUsers(),

        ]);
    }
    /**
     * @Route("/admin/account/update/", methods="PUT",name="updateAccount")
     */
    public function update(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $user=$entityManager->getRepository(User::class)->find($parametersAsArray['id']);
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$parametersAsArray['id']
            );
        }
        $user->setPassword($this->passwordHasher->hashPassword(
             $user,
             $parametersAsArray['password']
        ));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([
            'response' => $this->getDoctrine()->getRepository(User::class)->getUsers(),
        ]);
    }
    /**
     * @Route("/admin/account/delete/{id}", methods="DELETE",name="deleteAccount")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user=$entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No account found for id '.$id
            );
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json([
            'response' => $this->getDoctrine()->getRepository(User::class)->getUsers(),
        ]);
    }

}
