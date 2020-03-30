<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Achat;
use BackendBundle\Entity\ProdAchat;
use BackendBundle\Entity\Product;
use BackendBundle\Form\ProdAchatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Ob\HighchartsBundle\Highcharts\Highchart;



class AchatController extends Controller
{
    public function indexAction(Request $request){
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        if(!$achat){
          $achat=new Achat();
          $achat->setDate( new \DateTime('now') );
          $achat->setClientAddress($user->getUsername());
          $achat->setClientName($user->getUsername());
            $achats=$this->getDoctrine()->getRepository(Achat::class)->findAll();
            $longeur=count($achats);
            $ref="Reff".$user->getUsername();
            $ch="";
            for($i=0;$i<10-$longeur;$i++){
                $ch.='0';
            }
            $ref.=$ch.$longeur;
          $achat->setClientType($ref);
          $achat->setEtat(0);
          $achat->setQuantite(0);
            $em= $this->getDoctrine()->getManager();
            $em->persist($achat);
            $em->flush();
        }
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();

        if($request->isMethod('POST')){
            $name=$request->request->get('myInput');
            $products=$this->getDoctrine()->getRepository(Product::class)->findBy(array('productName'=>$name));

        }
        return $this->render('@Backend/Achat/index.html.twig',array('products'=>$products,'user'=>$user,'achat'=>$achat));


    }
    public function generateReference($user){
        $achats=$this->getDoctrine()->getRepository(Achat::class)->findAll();
        $longeur=count($achats);
        $ref="Reff".$user->getUsername();
        for($i=0;$i<10-$longeur;$i++){
            $ref=$ref."0";
        }
        $ref=$ref.$longeur;
        echo $ref;
    }

    public function readAction(Request $request){
        $achats=$this->getDoctrine()->getRepository(Achat::class)->findBy(array('etat'=>1));
        $paginator=$this->get("knp_paginator");
        $pagination = $paginator->paginate(
            $achats, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        $con=$this->getDoctrine()->getConnection();
        $sql1='
        SELECT DATE_FORMAT(date, \'%Y-%m-%d\') as d, COUNT(*) AS NBR FROM achat
         GROUP BY DATE_FORMAT(date, \'%Y-%m-%d\') order by d desc';
        $sql='
        SELECT client_address, COUNT(*) AS NBR FROM achat
        GROUP BY client_address order by NBR desc
        ';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $table=$stmt->fetchAll();
        $stmt = $con->prepare($sql1);
        $stmt->execute();
        $table1=$stmt->fetchAll();

        $series = array(
            array("name" => "Nombre d'achat",  "data" => array((int)$table1[0]["NBR"],(int)$table1[1]["NBR"],(int)$table1[2]["NBR"]))


        );
        $series1 = array(
            array("name" => "Nombre d'achat",  "data" => array(1,8,5))


        );

        $data1=[
            [$table[0]["client_address"],(int)$table[0]["NBR"]],
            [$table[1]["client_address"],(int)$table[1]["NBR"]],
            [$table[2]["client_address"],(int)$table[2]["NBR"]]
        ];

        $ob1 = new Highchart();
        $ob1->chart->renderTo('piechart');
        $ob1->chart->type('pie');
        $ob1->title->text('Les clients les plus fideles');
        $ob1->series(array(array("data"=>$data1)));


        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Les achats de trois dernier jours');
        $ob->xAxis->title(array('text'  => "les dates"));
        $ob->xAxis->categories(array($table1[0]["d"],$table1[1]["d"],$table1[2]["d"]));

        $ob->yAxis->title(array('text'  => "Nombre d'achat"));
        $ob->series($series1);
        return $this->render('@Backend/Achat/read.html.twig',array('pagination' => $pagination,'chart' => $ob,'piechart'=>$ob1));


    }
    public function pdfAction($id){
        $achat=$this->getDoctrine()->getRepository(Achat::class)->find($id);

        $prodachats= $this->getDoctrine()->getRepository(ProdAchat::class)
            ->findBy(array('achat'=>$achat));
        $html = $this->renderView('@Backend/Achat/pdf.html.twig', array(
            'a'  => $achat,
            'prodachats' => $prodachats,
        ));
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,array(
                'default-header'=>null,
                'encoding' => 'utf-8',
                'images' => true,
                'enable-javascript' => true,
                'margin-right'  => 7,
                'margin-left'  =>7,
            )),
            'file.pdf'
        );

    }
    public function detailsProduitAction($id){
        $product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        return $this->render('@Backend/Achat/show.html.twig',array('product'=>$product));


    }
    public function addAchatAction($id){
        $em= $this->getDoctrine()->getManager();
        $product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $prodachat=$this->getDoctrine()->getRepository(ProdAchat::class)->findOneBy(array('product'=>$product,'achat'=>$achat));
        if($prodachat){
            $achat->setQuantite($achat->getQuantite()+1);
            $prodachat->setQte($prodachat->getQte()+1);
            $em->persist($achat);
            $em->persist($prodachat);
            $em->flush();

        }
        else{
            $prodachat=new ProdAchat();
            $prodachat->setProduct($product);
            $prodachat->setAchat($achat);
            $prodachat->setQte(1);
            $achat->setQuantite($achat->getQuantite()+1);
            $em->persist($achat);
            $em->persist($prodachat);
            $em->flush();
        }
        $this->addFlash('success', 'Product ajouter au panier');

        return $this->redirectToRoute("index_achat");



    }
    public function panierAction(){
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array('achat'=>$achat));
        return $this->render('@Backend/Achat/panier.html.twig',array('prodachats'=>$prodachats));



    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $prodachat=$em->getRepository(ProdAchat::class)->find($id);
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $achat->setQuantite($achat->getQuantite()-$prodachat->getQte());
        $em->persist($achat);

        $em->remove($prodachat);
        $em->flush();
        $this->addFlash('success', 'Product deleted from basket');

        return $this->redirectToRoute("panier_achat");
    }
    public function editAction($id,Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $prodachat = $entityManager->getRepository(ProdAchat::class)->find($id);
        $form=$this->createForm(ProdAchatType::class,$prodachat)->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($prodachat);
            $em->flush();
            $this->addFlash('success', 'Quantity Product updated successfully');
            return $this->redirectToRoute("panier_achat");
        }
        return $this->render("@Backend/Achat/edit.html.twig",array(
            "form" =>$form->createView(),
        ));
    }
    public function confirmAction(){
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $achat->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($achat);
        $em->flush();
          // incremental auth


        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation Commande')
            ->setFrom('brahimhm470@gmail.com')
            ->setTo('brahimhmida95@gmail.com')
            ->setBody(
              "votre commande est confirmer avec ID ".$achat->getClientType(),
                'text/html'
            );
        $this->get('mailer')->send($message);
        $this->addFlash('success', 'Votre commande est confirmer et un mail a été envoyé');

        return $this->redirectToRoute("index_achat");
    }
    public function commandesAction(){
        $user=$this->getUser();
        $achats=$this->getDoctrine()->getRepository(Achat::class)->findBy(array('clientAddress'=>$user->getUsername(),'etat'=>1));
        $commandes=array();
        foreach($achats as $achat){
            $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array('achat'=>$achat));
            array_push($commandes,$prodachats);

        }
        return $this->render("@Backend/Achat/commandes.html.twig",array(
            'commandes'=>$commandes,

        ));

    }
}
