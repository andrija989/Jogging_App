<?php

namespace App\Controller;

use App\Exceptions\RecordNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Services\RecordService;
use App\Services\UserService;
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
     * @var RecordService $recordService
     */
    private $recordService;

    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * RecordController constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param RecordService $recordService
     * @param UserService $userService
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RecordService $recordService, UserService $userService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->recordService = $recordService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return Response
     *
     * @throws Exception
     */
    public function index(Request $request,int $id): Response
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');
        $reportDate = $request->get('week');
        $user = $this->userService->getUser($id);
        $records = $this->recordService->getRecords($id, $dateFrom, $dateTo)->getRecords();

         /**
         * Report getting with average speed and time
         */
        $averageTime = 0;
        if ($reportDate){
            $averageTime = $this->recordService->averageTime($records,$reportDate);
        }
        $averageDistance = 0;
        if ($reportDate){
            $averageDistance = $this->recordService->averageDistance($records, $reportDate);
        }

        $weeks = $this->recordService->getWeeks($records);

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
    public function store(Request $request, int $id): RedirectResponse
    {
        $date = new \DateTime();
        $distance = $request->get('distance');
        $time = $request->get('time');
        $user = $this->userService->getUser($id);

        $record = $user->createRecord($date, $distance, $time);
        $this->recordService->storeRecord($record);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $id]));
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function routeToEdit(int $id): Response
    {
       $record = $this->recordService->routeToEditRecord($id);

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
    public function updateRecord(Request $request,int $id): RedirectResponse
    {
        $distance = $request->get('distance');
        $time = $request->get('time');
        $this->recordService->updateRecord($id, $distance, $time);
        $record = $this->recordService->getRecord($id);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $record->getUser()->getId()]));
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws RecordNotFoundException
     */
    public function deleteRecord(int $id): RedirectResponse
    {
        $record = $this->recordService->getRecord($id);
        $this->recordService->deleteRecordById($id);

        return new RedirectResponse($this->urlGenerator->generate('home',['id' => $record->getUser()->getId()]));
    }
}
