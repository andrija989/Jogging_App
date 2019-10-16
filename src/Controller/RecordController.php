<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Record;
Use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecordController extends AbstractController
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/record", name="record")
     */
    public function index($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw new Exception('No user found under ID you search for');
        }

        return $this->render('Users/user.html.twig', ['user' => $user]);
    }

    public function store(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $record = new Record();
        $date = new \DateTime();
        $record->setDate($date);
        $record->setDistance($request->get('distance'));
        $record->setTime($request->get('time'));
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $record->setUser($user);

        $entityManager->persist($record);
        $entityManager->flush();

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }
}
