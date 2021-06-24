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
    /**
     * @Route("/admin/map/insert/",methods={"POST"}, name="saveMap")
     */
    public function saveMap(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $formMap = $this->getDoctrine()->getRepository(Formmap::class);
        $formMap = $this->getDoctrine()->getRepository(Formmap::class);
        $map = $formMap->findOneBy(array('formid' => $parametersAsArray['formId']));
        if ($map) {
            //updateMap
            $formMap->updateMap($parametersAsArray['formId'],$parametersAsArray['map']);
        } else {
            //insertMap
            $formMap->saveMap($parametersAsArray['formId'],$parametersAsArray['map']);
        }
        $formPdfImage = $this->getDoctrine()->getRepository(Formpdfimage::class);
        $image = $formPdfImage->findOneBy(
            array('formid' => $parametersAsArray['formId'])
        );
        $response='no';
        if ($image) {
            //updateMap
            $formPdfImage->updateImages($parametersAsArray['formId'],$parametersAsArray['images']);
        } else {
            //insertMap
            $formPdfImage->saveImages($parametersAsArray['formId'],$parametersAsArray['images']);
        }
        return $this->json([
            'response'=> $response
        ]);
    }

}
