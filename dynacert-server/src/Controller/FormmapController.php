<?php

namespace App\Controller;

use App\Entity\Formmap;
use App\Entity\Formpdfimage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormmapController extends AbstractController
{
    /**
     * @Route("/admin/map/{id}",methods={"GET"}, name="getMap")
     */
    public function index($id): Response
    {
        $formMapRepository = $this->getDoctrine()->getRepository(Formmap::class);
        $response=$formMapRepository->getMaps($id);
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
    /**
     * @Route("/admin/map/insert/",methods={"POST"}, name="saveMap")
     */
    public function saveMap(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        $entityManager = $this->getDoctrine()->getManager();
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $map=$entityManager->getRepository(Formmap::class)->findOneBy(array('formid' => $parametersAsArray['formId']));
        if ($map) {
            $map->setFormId($parametersAsArray['formId']);
            $map->setMap($parametersAsArray['map']);
            $entityManager->flush();
        } else {
            $formMap=new Formmap();
            $formMap->setFormId($parametersAsArray['formId']);
            $formMap->setMap($parametersAsArray['map']);
            $entityManager->persist($formMap);
            $entityManager->flush();
        }
        $image=$entityManager->getRepository(Formpdfimage::class)->findOneBy(array('formid' => $parametersAsArray['formId']));
        $response='ok';
        if ($image) {
            //updateMap
            $image->setFormId($parametersAsArray['formId']);
            $image->setImages($parametersAsArray['images']);
            $entityManager->flush();
        } else {
            $formPdfImage=new Formpdfimage();
            $formPdfImage->setFormId($parametersAsArray['formId']);
            $formPdfImage->setImages($parametersAsArray['images']);
            $formPdfImage->setDate(new \DateTime('now'));
            $entityManager->persist($formPdfImage);
            $entityManager->flush();
        }
        return $this->json([
            'response'=> $response,
        ]);
    }

}
