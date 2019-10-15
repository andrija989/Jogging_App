<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function index()
    {
        return $this->render('security/register.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }

    public function store(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setEmail($request->get('email'));
        $password = $request->get('password');
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);
        $user->setRoles('ROLE_USER');
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Saved new user with id '.$user->getId());
    }
}
