<?php

namespace App\Controller;

use App\Entity\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/admin/form", methods={"GET"}, name="form")
     */
    public function index(): Response
    {
        $form = $this->getDoctrine()->getRepository(Form::class);
        return $this->json([
            'response' => $form->getForms()
        ]);
    }
    /**
     * @Route("/admin/form/{id}",methods={"GET"}, name="getConfigurationForm")
     */
    public function getConfigurationForm($id)
    {
        $form = $this->getDoctrine()->getRepository(Form::class);
        $response=$form->getConfigurationForm($id);
        return $this->json([
            'form' => $response[0]['config']['form'],
            'title' => $response[0]['title'],
            'category' => $response[0]['category'],
        ]);  
    }
    /**
     * @Route("/admin/form/insert/",methods={"POST"}, name="saveForm")
     */
    public function insertForm(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $form = $this->getDoctrine()->getRepository(Form::class);
        $response=$form->insertForm($content);
        return $this->json([
            'repsonse'=> $form->getForms()
        ]);  
    }
}
