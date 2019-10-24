<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\ORMException;
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
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UrlGeneratorInterface $urlGenerator,
        UserRepositoryInterface $userRepository)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
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
     *
     * @throws ORMException
     */
    public function store(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $role = 'ROLE_USER';
        $user = new User($email, $role);
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $this->userRepository->add($user);

        return new RedirectResponse($this->urlGenerator->generate('login'));
    }
}
