<?php

namespace App\Controller;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorController extends AbstractController
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * AdministratorController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $users = $this->userService->showUsers();

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function deleteUser(int $id)
    {
        $this->userService->deleteUser($id);

        return $this->redirectToRoute('administrator-page');
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function updateUser(Request $request,int $id)
    {
        $users = $this->userService->showUsers();
        $role = $request->get('role');

        $this->userService->updateUserRole($id, $role);

        return $this->render('Users/administrator.html.twig', ['users' => $users]);
    }
}

