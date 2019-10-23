<?php

namespace App\Controller;

use App\Entity\Record;
use App\Exceptions\RecordNotFoundException;
use App\Filters\Builders\RecordFilterBuilder;
use App\Repository\Interfaces\RecordRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\RecordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecordController extends AbstractController
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var RecordRepository $recordRepository
     */
    private $recordRepository;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * RecordController constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @param RecordRepositoryInterface $recordRepository
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RecordRepositoryInterface $recordRepository, UserRepositoryInterface $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->recordRepository = $recordRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @param $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function index(Request $request, $id)
    {
        $filter = RecordFilterBuilder::valueOf()
            ->setUserId($id)
            ->setDateFrom($request->get('dateFrom'))
            ->setDateTo($request->get('dateTo'));

        $reportDate = $request->get('week');
        $user = $this->userRepository->ofId($id);
        $records = $this->recordRepository->filter($filter->build());

        /**
         * Report getting with average speed and time
         */
        $time = 0;
        $averageTime = 0;
        $distance = 0;
        $averageDistance = 0;
        $counter = 0.0001;
        foreach($records as $record) {
            if ( $record->getDate()->format('W') == $reportDate)
            {
                $distance += $record->getDistance();
                $time += $record->getTime();
                $counter++;
            }
            $averageTime = $time / $counter;
            $averageDistance = $distance / $counter;
        }
        /**
         * Getting weeks for dropdown menu in reports
         */
        $weeks = [];
        foreach ($records as $record)
        {
            $weeks[] += $record->getDate()->format('W');
        }
        $weeks = array_unique($weeks);

        return $this->render('record/record.html.twig', [
            'user' => $user,
            'records' => $records,
            'averageDistance' => $averageDistance,
            'averageTime' => $averageTime,
            'reportDate' => $reportDate,
            'weeks' => $weeks]);
    }

    /**
     * @param Request $request
     *
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function store(Request $request, $id)
    {
        $record = new Record();
        $record->setDate(new \DateTime());
        $record->setDistance($request->get('distance'));
        $record->setTime($request->get('time'));
        $user = $this->userRepository->ofId($id);
        $record->setUserId($user);

        $this->recordRepository->add($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    /**
     * @param $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function edit($id)
    {
       $record = $this->recordRepository->ofId($id);

       return $this->render('record/edit.html.twig', ['record' => $record]);

    }

    /**
     * @param Request $request
     *
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws RecordNotFoundException
     */
    public function updateRecord(Request $request,$id)
    {
        $record = $record = $this->recordRepository->ofId($id);
        $user = $record->getUser();
        $record->setDistance(($request->get('distance')));
        $record->settime(($request->get('time')));
        $this->recordRepository->add($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws RecordNotFoundException
     * @throws OptimisticLockException
     */
    public function deleteRecord($id)
    {
        $record =  $this->recordRepository->ofId($id);
        $user = $record->getUser();
        $this->recordRepository->remove($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }
}
