<?php

namespace App\Controller;

use App\Exceptions\RecordNotFoundException;
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

    /**
     * RecordController constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param RecordService $recordService
     * @param UserService $userService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RecordService $recordService,
        UserService $userService,
        TokenStorageInterface $tokenStorage
    ){
        $this->urlGenerator = $urlGenerator;
        $this->recordService = $recordService;
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return Response
     *
     * @throws Exception
     */
    public function index(Request $request, $id): Response
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');
        $reportDate = $request->get('week');
        $user = $this->userService->getUser($id);
        $token = $this->tokenStorage->getToken();

        $records = $this->recordService
            ->getRecords($user->getId(), $dateFrom, $dateTo)
            ->getRecords();

         /**
         * Report getting with average speed and time
         */
        $averageTime = 0;
        if ($reportDate){
            $averageTime = $this->recordService
                ->averageTime($records,$reportDate);
        }
        $averageDistance = 0;
        if ($reportDate){
            $averageDistance = $this->recordService
                ->averageDistance($records, $reportDate);
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
     *
     * @return RedirectResponse
     *
     * @throws ORMException
     */
    public function store(Request $request): RedirectResponse
    {
        $date = new \DateTime();
        $distance = $request->get('distance');
        $time = $request->get('time');
        $user = $this->tokenStorage->getToken()->getUser();

        $this->recordService->storeRecord($date, $distance, $time, $user);

        return new RedirectResponse(
            $this->urlGenerator
                ->generate('home',['id' => $user->getId()])
        );
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
       $record = $this->recordService->getRecord($id);

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


        return new RedirectResponse(
            $this->urlGenerator
                ->generate('home',['id' => $record->getUser()->getId()])
        );
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

        return new RedirectResponse(
            $this->urlGenerator
                ->generate('home',['id' => $record->getUser()->getId()])
        );
    }
}
