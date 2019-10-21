<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Record;
use Cassandra\Date;
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
    public function index(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id); // getting user, and records in records view
        $dateFrom = $request->get('dateFrom'); // record filter add-on
        $dateTo = $request->get('dateTo');
        $reportDate = $request->get('week');

        $records = $user->getRecords();
        $distance = 0;
        $time = 0;
        $averageDistance = 0;
        $averageTime = 0;
        $counter = 0.0001;

        if (isset($reportDate)) { // report filter logic//
            foreach($records as $record) {
                if ( $record->getDate()->format('W') == $reportDate)
                {
                    $distance += $record->getDistance();
                    $time += $record->getTime();
                    $counter++;

                }
                $averageDistance = $distance / $counter;
                $averageTime = $time / $counter;
            }
        }

        $weeks = [];
        foreach ($records as $record) // report select option getting weeks
        {
            $weeks[] += $record->getDate()->format('W');
        }
        $weeks = array_unique($weeks);

        if (!$user) {
            throw new Exception('No user found under ID you search for');
        }

        return $this->render('record/record.html.twig', [
            'user' => $user,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'averageDistance' => $averageDistance,
            'averageTime' => $averageTime,
            'reportDate' => $reportDate,
            'weeks' => $weeks]);
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

    public function edit($id)
    {
        {
            $record = $this->getDoctrine()->getRepository(Record::class)->find($id);

            return $this->render('record/edit.html.twig', ['record' => $record]);
        }
    }


    public function updateRecord(Request $request,$id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $record = $this->getDoctrine()->getRepository(Record::class)->find($id);
        $record->setDistance(($request->get('distance')));
        $record->settime(($request->get('time')));

        $entityManager->persist($record);
        $entityManager->flush();

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    public function deleteRecord($id)
    {
        $em = $this->getDoctrine()->getManager();
        $record = $this->getDoctrine()->getRepository(Record::class)->find($id);
        $user = $this->getUser();

        $em->remove($record);
        $em->flush();

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));

    }


}
