<?php

namespace App\Controller;
use App\Entity\Form;
use App\Entity\Category;
use App\Entity\Formmap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewerController extends AbstractController
{
    /**
     * @Route("/viewer/category", methods={"GET"}, name="viewerShowCategory")
     */
    public function index(): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class);
        return $this->json([
            'response' => $category->getCategories(),
        ]);
    }
    /**
     * @Route("/viewer/category/{id}",methods={"GET"}, name="viewerGetConfigurationForm")
     */
    public function listFormById($id): Response
    {
        $form = $this->getDoctrine()->getRepository(Form::class);
        $response=[];
        if ($id!='null') $response=$form->getFormsByCategoryId($id);
        return $this->json([
            'response' => $response
        ]);
    }

    
    /**
     * @Route("/viewer/config/{id}",methods={"GET"}, name="vieweGetConfigurationForm")
     */
    public function getConfigurationForm($id)
    {
        $form = $this->getDoctrine()->getRepository(Form::class);
        $response=$form->getConfigurationForm($id);
        return $this->json([
            'form' => json_decode($response[0]['config']['form']),
            'title' => $response[0]['title'],
            'category' => $response[0]['category'],
        ]);  
    }
    /**
     * @Route("/viewer/pdfconfig/{id}",methods={"GET"}, name="vieweGetConfigurationPdf")
     */
    public function getConfigurationPdf($id): Response
    {
        $formMap = $this->getDoctrine()->getRepository(Formmap::class);
        $response=$formMap->getMaps($id);
        $jsonmap = array();
        $imagesMap= array();
        if (count($response)==1) {
            $map=$response[0]['map'];
            $images=$response[0]['images'];
            if (is_array($map) == FALSE) {
                $jsonmap = json_decode($map,true);
                if (is_string($jsonmap)) $jsonmap = json_decode($jsonmap);
            
            } 
            if (is_array($images) ==FALSE) {
                $imagesMap=json_decode($images);  
                if (is_string($imagesMap)) $imagesMap=json_decode($imagesMap);  
            }
        } 
        return $this->json([
                'map' => $jsonmap,
                'images' => $imagesMap,
            ]);
            
               
    }
}
