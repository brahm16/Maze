<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Achat;
use BackendBundle\Entity\Fournisseur;
use BackendBundle\Entity\Note;
use BackendBundle\Entity\ProdAchat;
use BackendBundle\Entity\Product;
use BackendBundle\Form\ProdAchatFourType;
use BackendBundle\Form\ProdAchatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;





class AchatController extends Controller
{
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
    public function indexAction(Request $request){
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        if(!$achat){
          $achat=new Achat();
          $achat->setDate( new \DateTime('now') );
          $achat->setClientAddress($user->getUsername());
          $achat->setClientName('client');
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
            array("name" => "Nombre d'achat",  "data" => array((int)$table1[2]["NBR"],(int)$table1[1]["NBR"],(int)$table1[0]["NBR"]))


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
        $ob->xAxis->categories(array($table1[2]["d"],$table1[1]["d"],$table1[0]["d"]));

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
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
    public function detailsProduitAction($id){
        $user=$this->getUser();
        $product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $note=$this->getDoctrine()->getRepository(Note::class)->findOneBy(array("product"=>$product,"client"=>$user->getUsername()));
        $valeur=0;
        if($note)
            $valeur=$note->getValeur();
        return $this->render('@Backend/Achat/show.html.twig',array('product'=>$product,'valeur'=>$valeur));


    }
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
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
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
    public function panierAction(){
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array('achat'=>$achat));
        return $this->render('@Backend/Achat/panier.html.twig',array('prodachats'=>$prodachats));



    }
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
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
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
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
    public function commandesAction(Request $request){
        $user=$this->getUser();
        $achats=$this->getDoctrine()->getRepository(Achat::class)->findBy(array('clientAddress'=>$user->getUsername(),'etat'=>1));
        $commandes=array();
        foreach($achats as $achat){
            $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array('achat'=>$achat));
            array_push($commandes,$prodachats);

        }
        if($request->isMethod('POST')){
            $d=$request->request->get("d");
            $date=date("Y-m-d", strtotime($d) );
            $user=$this->getUser();
            $client=$user->getUsername();
            $achats=$this->getDoctrine()->getRepository(Achat::class)->findByDateParametre($date,1,$client);
            $commandes=array();
            foreach($achats as $achat){
                $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array('achat'=>$achat));
                array_push($commandes,$prodachats);

            }

        }

        return $this->render("@Backend/Achat/commandes.html.twig",array(
            'commandes'=>$commandes,

        ));

    }
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
    public function addNoteAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $note=new Note();
        $valeur=$request->request->get("rate");
        $id=$request->request->get("p");
        $product=$this->getDoctrine()->getRepository(Product::class)->find((int)$id);
        $note=$this->getDoctrine()->getRepository(Note::class)->findOneBy(array("product"=>$product));
        if($note){
            $note->setEtat(1);
            $note->setValeur((int)$valeur);
            $note->setDateEdit(new \DateTime('now'));
            $em->flush();


        }
        else{
            $note=new Note();
            $note->setEtat(0);
            $note->setValeur((int)$valeur);
            $note->setProduct($product);
            $note->setClient($user->getUsername());
            $note->setDateNote( new \DateTime('now') );
            $note->setDateEdit(new \DateTime('now'));
            $em->persist($note);
            $em->flush();
        }


        return $this->redirect($this->generateUrl('index_achat_show',array('id' => $id)));

    }
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_CLIENT"}, statusCode=404, message="Tu n'est pas l'authorisation")
     */
    public function deleteNoteAction($id){
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $product=$this->getDoctrine()->getRepository(Note::class)->find((int)$id);
        $note=$this->getDoctrine()->getRepository(Note::class)->findOneBy(array("product"=>$product,"client"=>$user->getUsername()));
        $em->remove($note);
        $em->flush();
        return $this->redirect($this->generateUrl('index_achat_show',array('id' => $id)));


    }
    public function  acheterOneAction(Request $request){
        $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        if($request->isMethod('POST')){
            $f=$request->request->get('f');
            $this->addFlash('success', 'Il faut maintenant ajouter produit');

            return $this->redirect($this->generateUrl('achat_achter_two',array('f' => $f)));

        }
        return $this->render('@Backend/Achat/achterOne.html.twig',array('fournisseurs'=>$fournisseurs));

    }
    public function acheterTwoAction(Request $request,$f){
        $rr=$f;
        $user=$this->getUser();
        $achats=$this->getDoctrine()->getRepository(Achat::class)->findAll();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>3));
        if(!$achat){
            $em= $this->getDoctrine()->getManager();

            $achat=new Achat();
            $achat->setDate( new \DateTime('now') );
            $achat->setClientAddress($user->getUsername());
            $achat->setClientName($rr);
            $longeur=count($achats);
            $achat->setClientType('four'.$rr.$longeur);
            $achat->setEtat(3);
            $achat->setQuantite(0);
            $em->persist($achat);
            $em->flush();
        }
        $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array("achat"=>$achat));

        $prodAchat=new ProdAchat();
        $form= $this->createForm(ProdAchatFourType::class,$prodAchat);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $achat->setQuantite($achat->getQuantite()+$prodAchat->getQte());
            $em->persist($achat);
            $em->flush();
            $prodAchat->setAchat($achat);
            $product=$prodAchat->getProduct();
            $prodAchat1=$this->getDoctrine()->getRepository(ProdAchat::class)->findOneBy(array("product"=>$product,"achat"=>$achat));
            if($prodAchat1){
                $prodAchat1->setQte($prodAchat1->getQte()+$prodAchat->getQte());
                $em->flush();
            }
            else{
                $em->persist($prodAchat);
                $em->flush();
            }

            $this->addFlash('success', 'Produit est ajouté');

            return $this->render('@Backend/Achat/achterTwo.html.twig',array("form"=>$form->createView(),'f'=>$f,"prodachats"=>$prodachats));


        }
        return $this->render('@Backend/Achat/achterTwo.html.twig',array("form"=>$form->createView(),'f'=>$f,"prodachats"=>$prodachats));

    }
    public function deleteAchatAction($id){
        $em = $this->getDoctrine()->getManager();
        $prodachat=$em->getRepository(ProdAchat::class)->find($id);
        $user=$this->getUser();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$user->getUsername(),'etat'=>0));
        $f=$achat->getClientName();
        $achat->setQuantite($achat->getQuantite()-$prodachat->getQte());
        $em->persist($achat);

        $em->remove($prodachat);
        $em->flush();
        $this->addFlash('success', 'Product deleted ');
        return $this->redirect($this->generateUrl('achat_achter_two',array('f' => $f)));


    }
    public function sendMailConfirmAction(){
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $address=$user->getUsername();
        $achat=$this->getDoctrine()->getRepository(Achat::class)->findOneBy(array('clientAddress'=>$address,'etat'=>3));
        $achat->setEtat(4);
        $em->flush();

        $prodachats=$this->getDoctrine()->getRepository(ProdAchat::class)->findBy(array("achat"=>$achat));

        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation Commande')
            ->setFrom('brahimhm470@gmail.com')
            ->setTo('brahimhmida95@gmail.com')



        ->setBody(
                $this->renderView(
                    '@Backend/Achat/sendMail.html.twig',
                    array("achat"=>$achat,"prodachats"=>$prodachats)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
        $this->addFlash('success', 'Mail est envoyé à la fournisseur');

        return $this->redirectToRoute('achat_achter_one');

    }


}
