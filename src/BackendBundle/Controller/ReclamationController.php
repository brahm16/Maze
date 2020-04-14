<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Reclamation;
use BackendBundle\Entity\Task;
use BackendBundle\Entity\User;
use BackendBundle\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;

class ReclamationController extends Controller
{
    public function count()
    {
        $count = 0;
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository("BackendBundle:Reclamation")->findBy(array("priority"=>1));
        foreach ($commentaire as $e){
            $count = $count + 1;
        }

        return $count;

    }

    public function ajouterAction(Request $request)
    {
        $task = new Task();
        $username=$this->getUser()->getUsername();
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('username'=>$username));
        $rec = new Reclamation();
        $rec->setIdUser($user->getIDU());
        $rec->setUsername($this->getUser()->getUsername());
        $rec->setUserMail($this->getUser()->getEmail());
        $rec->setDate(new \DateTime('now'));
        $rec->setEtat('non traité');
        $form = $this->createForm(ReclamationType::class,$rec);
        $form->handleRequest($request);
        if($form-> isSubmitted() && $form->isValid()) {
            $task->setUserMail($this->getUser()->getEmail());
            $task->setIdUser($user);
            $task->setUsername($this->getUser()->getUsername());
            $task->setDate($rec->getDate()->format('Y-m-d H:i:s'));
            $task->setDescription('le client sous le nom d\'utilsateur '.$this->getUser()->getUsername().
                ' a ajouté une nouvelle réclamation concernant le produit '.$rec->getIdProduct()->getProductName().
                ', le : '.$rec->getDate()->format('Y-m-d H:i:s'));
            $em = $this->getDoctrine()->getManager();

            $sid    = "AC02f86af29ff3caf5c5b0fab5a5402c42";
            $token  = "72bca602aa940340d414e252fca2cfdd";
            $client = new Client($sid, $token);
            $message = $client->messages->create(
                '+21690118795', // Text this number
                [
                    'from' => '+16208786068', // From a valid Twilio number
                    'body' => 'une nouvelle reclamation !'
                ]
            );





            $em->persist($rec);
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('ajouter_reclamation');
        }
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Reclamation::class)->findArticleQBTitre($this->getUser()->getId());
        return $this->render('@Backend/Reclamation/Ajouter.html.twig',
            array('formulaire'=>$form->createView(),
                'article'=>$article));
    }
    public function UpdateAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $username=$this->getUser()->getUsername();
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('username'=>$username));
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReclamationType::class,$reclamation);
        $reclamation->setDate(new \DateTime('now'));
        $form->handleRequest($request);
        if($form-> isSubmitted()) {
            $task = new Task();
            $task->setUserMail($this->getUser()->getEmail());
            $task->setIdUser($user);
            $task->setUsername($this->getUser()->getUsername());
            $task->setDate($reclamation->getDate()->format('Y-m-d H:i:s'));
            $task->setDescription('le client sous le nom d\'utilsateur '.$this->getUser()->getUsername().
                ' a mis à jour son réclamation dont l\'id : '.$reclamation->getId().' concernant le produit '.$reclamation->getIdProduct()->getProductName().
                ', le : '.$reclamation->getDate()->format('Y-m-d H:i:s'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('ajouter_reclamation');
        }
        return $this->render('@Backend/Reclamation/Ajouter.html.twig',
            array('formulaire'=>$form->createView(),
                'article'=>$reclamation
                ));

    }
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $username=$this->getUser()->getUsername();
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('username'=>$username));
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $task = new Task();

        $task->setUserMail($this->getUser()->getEmail());
        $task->setIdUser($user);
        $task->setUsername($this->getUser()->getUsername());
        $task->setDate($reclamation->getDate()->format('Y-m-d H:i:s'));
        $task->setDescription('le client sous le nom d\'utilsateur '.$this->getUser()->getUsername().
            ' a supprimé son réclamation dont l\'id : '.$id.' concernant le produit '.$reclamation->getIdProduct()->getProductName().
            ', le : '.$reclamation->getDate()->format('Y-m-d H:i:s'));
        $em->remove($reclamation);
        $em->persist($task);
        $em->flush();
        return $this->redirectToRoute('ajouter_reclamation');


    }

    public function listAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->render('@Backend/Reclamation/adminManager.html.twig',
            array('articles'=>$reclamation,
                  'tasks'=>$tasks));
    }

    public function updateEtatAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $reclamation->setEtat('traité');
        $em->persist($reclamation);
        $em->flush();
        return $this->redirectToRoute('reclamation_admin');
    }



}
