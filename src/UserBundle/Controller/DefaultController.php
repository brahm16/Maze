<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        $staffs =$this->getDoctrine()->getRepository('BackendBundle:Staff')->findAll();
        return $this->render('@User/Admin/base2.html.twig', array(
            'staffs' => $staffs,'users' => $users
        ));
    }
}
