<?php

namespace App\Controller;

use App\Entity\Record;
use App\Filters\RecordFilter;
use App\Repository\Interfaces\RecordRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\RecordRepository;
use App\Repository\UserRepository;
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

    /**
     * @var RecordRepository
     */
    private $recordRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RecordFilter
     */
    private $filter;

    /**
     * RecordController constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param RecordRepositoryInterface $recordRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RecordRepositoryInterface $recordRepository, UserRepositoryInterface $userRepository, RecordFilter $filter)
    {
        $this->urlGenerator = $urlGenerator;
        $this->recordRepository = $recordRepository;
        $this->userRepository = $userRepository;
        $this->filter = $filter;
    }

    /**
     * @Route("/record", name="record")
     */
    public function index(Request $request, $id)
    {
        $filter = new RecordFilter($id);
        $user = $this->userRepository->ofId($id);
        $filter->setDateFrom($request->get('dateFrom'));
        $filter->setDateTo($request->get('dateTo'));
        $reportDate = $request->get('week');
        $records = $this->filter->getUserId();

        if (isset($filter)) {
            $records = $this->recordRepository->filter($filter);
        }

        /**
         * Report getting with average speed and time
         */
        $averageTime= $this->recordRepository->averageTime($records, $reportDate);
        $averageDistance = $this->recordRepository->averageDistance($records,$reportDate);
        $weeks = $this->recordRepository->weeksNumber($records);


        return $this->render('record/record.html.twig', [
            'user' => $user,
            'records' => $records,
            'averageDistance' => $averageDistance,
            'averageTime' => $averageTime,
            'reportDate' => $reportDate,
            'weeks' => $weeks]);
    }

    public function store(Request $request, $id)
    {
        $record = new Record();
        $record->setDate(new \DateTime());
        $record->setDistance($request->get('distance'));
        $record->setTime($request->get('time'));
        $user = $this->userRepository->ofId($id);
        $record->setUser($user);

        $this->recordRepository->add($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    public function edit($id)
    {
       $record = $this->recordRepository->ofId($id);

       return $this->render('record/edit.html.twig', ['record' => $record]);

    }


    public function updateRecord(Request $request,$id)
    {
        $record = $record = $this->recordRepository->ofId($id);
        $user = $record->getUser();
        $record->setDistance(($request->get('distance')));
        $record->settime(($request->get('time')));
        $this->recordRepository->add($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    public function deleteRecord($id)
    {
        $record =  $this->recordRepository->ofId($id);
        $user = $record->getUser();

        $this->recordRepository->remove($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));

    }


}
