<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Entrepot;
use BackendBundle\Form\EntrepotType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EntrepotController extends Controller
{

    public function addEntrepotAction(Request $request){

        $entrepot=new Entrepot();
        $form=$this->createForm( EntrepotType::class,$entrepot);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($entrepot);
            $em->flush();
        }
        return $this->render('@Backend/Entrepot/addentrepot.html.twig',array('form'=>$form->createView()));

    }



    public function listEntrepotAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $entrepots=$em->getRepository(Entrepot::class)->findAll();
        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entrepots,
            $request->query->getInt('page', 1), /*page number*/
            4/*limit per page*/
        );
        return $this->render('@Backend/Entrepot/Listentrepot.html.twig',array('entrepots'=>$pagination));

    }


    public function updateEntrepotAction(Request $request , $id){

        $em=$this->getDoctrine()->getManager();
        $entrepots=$em->getRepository(Entrepot::class)->find($id);
        $form=$this->createForm(EntrepotType::class,$entrepots);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($entrepots);
            $em->flush();
        }

        return $this->render('@Backend/Entrepot/updateentrepot.html.twig',array('form'=>$form->createView()));
    }


    public function deleteEntrepotAction(Request $request , $id){
        $entrepots=new Entrepot();
        $em=$this->getDoctrine()->getManager();
        $entrepots=$em->getRepository(Entrepot::class)->find($id);

        $em->remove($entrepots);
        $em->flush();

        return $this->redirectToRoute('list_entrepot');
    }


}
