<?php

namespace App\Controller;

use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;

class AdministratorController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AdministratorController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepository->filter();

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteUser($id)
    {
        $user = $this->userRepository->ofId($id);
        $this->userRepository->remove($user);

        return $this->redirectToRoute('administrator-page');

    }

    /**
     * @param Request $request
     *
     * @param int $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateUser(Request $request,$id)
    {
        $user = $this->userRepository->ofId($id);
        $users = $this->userRepository->filter();
        $user->setRoles(($request->get('role')));

        $this->userRepository->add($user);

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }
}

