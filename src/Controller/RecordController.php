<?php

namespace App\Controller;

use App\Exceptions\RecordNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Filters\Builders\RecordFilterBuilder;
use App\Repository\Interfaces\RecordRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\RecordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
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
     * @param RecordRepositoryInterface $recordRepository
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
     * @param int $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function index(Request $request,int $id)
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
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws UserNotFoundException
     */
    public function store(Request $request, int $id)
    {
        $date = new \DateTime();
        $distance = $request->get('distance');
        $time = $request->get('time');
        $user = $this->userRepository->ofId($id);

        $record = $user->createRecord($date, $distance, $time);
        $this->recordRepository->add($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function edit(int $id)
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
    public function updateRecord(Request $request,int $id)
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
     */
    public function deleteRecord($id)
    {
        $record =  $this->recordRepository->ofId($id);
        $user = $record->getUser();
        $this->recordRepository->remove($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $user->getId()]));
    }
}
