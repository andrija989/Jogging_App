<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface $passwordEncoder
     */
    private $passwordEncoder;
    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private $urlGenerator;

    /**
     * RegisterController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,UrlGeneratorInterface $urlGenerator)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return $this->render('security/register.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
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
        return new RedirectResponse($this->urlGenerator->generate('login'));
    }
}
