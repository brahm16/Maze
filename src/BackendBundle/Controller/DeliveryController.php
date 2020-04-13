<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Delivery;
use BackendBundle\Entity\Vehicule;
use BackendBundle\Form\DeliveryType;
use BackendBundle\Form\VehiculeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DeliveryController extends Controller
{
    public function ajoutDeliveryAction(Request $request)
    {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delivery);
            $em->flush();
            return $this->redirectToRoute("esprit_afficheDelivery");
        }
        return $this->render("@Backend/Delivery/ajoutD.html.twig", array('form' => $form->createView()));
    }

    public function afficheDeliveryAction()
    {
        $em=$this->getDoctrine()->getManager();
        $delivery=$em->getRepository("BackendBundle:Delivery")->findAll();
        return $this->render('@Backend/Delivery/afficheD.html.twig',array('delivery'=>$delivery));
    }

    public function supprimeDeliveryAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $delivery= $em->getRepository(Delivery::class)->find($id);
        $em->remove($delivery);
        $em->flush();
        return $this->redirectToRoute("esprit_afficheDelivery");
    }

    public function afficheVehiculeAction()
    {
        $em=$this->getDoctrine()->getManager();
        $vehicule=$em->getRepository("BackendBundle:Vehicule")->findAll();
        return $this->render('@Backend/Vehicule/afficheV.html.twig',array('vehicule'=>$vehicule));
    }

    public function ajoutVehiculeAction(Request $request)
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vehicule);
            $em->flush();
            return $this->redirectToRoute("esprit_afficheVehicule");
        }
        return $this->render("@Backend/Vehicule/ajoutV.html.twig", array('form' => $form->createView()));
    }

    public function supprimeVehiculeAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $vehicule= $em->getRepository(Vehicule::class)->find($id);
        $em->remove($vehicule);

        $em->flush();
        return $this->redirectToRoute("esprit_afficheVehicule");
    }

    public function exportAction()
    {
        $em=$this->getDoctrine()->getManager();
        $deliverys=$em->getRepository('BackendBundle:Delivery')->findAll();
        $writer = $this->container->get('egyg33k.csv.writer');
        $csv = $writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Reference', 'Client Name', 'Driver Name']);

        foreach ($deliverys as $d)
        {
            $csv->insertOne([$d->getReference(), $d->getClientName(), $d->getDriverName()]);
        }
        $csv->output('delivery.csv');
        die('export');
    }
}
