<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AdministratorController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AdministratorController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/administrator", name="administrator")
     */
    public function index()
    {
        $users = $this->userRepository->findAll();

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }

    public function deleteUser($id)
    {
        $user = $this->userRepository->ofId($id);
        $this->userRepository->remove($user);

        return $this->redirectToRoute('administrator-page');

    }
    public function updateUser(Request $request,$id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->userRepository->ofId($id);
        $users = $this->userRepository->findAll();
        $user->setRoles(($request->get('role')));

        $this->userRepository->add($user);

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }
}

