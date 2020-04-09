<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Stock;
use BackendBundle\Form\StockType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class StockController extends Controller
{
    public function addStockAction(Request $request){

        $stock=new Stock();
        $form=$this->createForm( StockType::class,$stock);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($stock);
            $em->flush();
        }
        return $this->render('@Backend/Stock/addstock.html.twig',array('form'=>$form->createView()));

    }



    public function listStockAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $stocks=$em->getRepository(Stock::class)->findAll();
        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $stocks,
            $request->query->getInt('page', 1), /*page number*/
            4/*limit per page*/
        );
        return $this->render('@Backend/Stock/Liststock.html.twig',array('stocks'=>$pagination));

    }


    public function updateStockAction(Request $request , $id){

        $em=$this->getDoctrine()->getManager();
        $stocks=$em->getRepository(Stock::class)->find($id);
        $form=$this->createForm(StockType::class,$stocks);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($stocks);
            $em->flush();
        }

        return $this->render('@Backend/Stock/updatestock.html.twig',array('form'=>$form->createView()));
    }


    public function deleteStockAction(Request $request , $id){
        $stocks=new Stock();
        $em=$this->getDoctrine()->getManager();
        $stocks=$em->getRepository(Stock::class)->find($id);
        $em->remove($stocks);
        $em->flush();

        return $this->redirectToRoute('list_stock');
    }

}
