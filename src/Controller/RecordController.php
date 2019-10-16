<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Record;
Use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
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

    public function store()
    {
        
    }
}
