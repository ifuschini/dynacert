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
     * @Route("/admin/form/save/",methods={"POST","PUT"}, name="saveForm")
     */
    public function insertForm(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        $content = $request->getContent();
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Form::class);
        $json = json_decode($content);
        $formTitle=$json->formTitle;
        $idForm = $json->IDForm;
        $categorySelected = $json->categorySelected;
        $form=$entityManager->getRepository(Form::class)->find($idForm);
        if (!$form) {
            $form= new Form();
        }
        $form->setTitle($formTitle);
        $form->setCategory($categorySelected);
        $form->setConfig($content);
        $form->setDate(new \DateTime('now'));
        $entityManager->persist($form);
        $entityManager->flush();
        return $this->json([
            'response'=> $repository->getForms()
        ]);  
    }
}
