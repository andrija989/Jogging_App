<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AdministratorController extends AbstractController
{
    /**
     * @Route("/administrator", name="administrator")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }

    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usrRepo = $em->getRepository(User::class);

        $user = $usrRepo->find($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('administrator-page');

    }
    public function updateUser(Request $request,$id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $user->setRoles(($request->get('role')));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }
}

