<?php

namespace App\Controller\Api;

use App\DataTransferObjects\ListRecordsDTO;
use App\Services\RecordService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/records")
 */
class ApiRecord extends AbstractController
{
    /**
     * @var RecordService $recordsService
     */
    private $recordsService;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ApiRecord constructor.
     * @param RecordService $recordService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(RecordService $recordService, TokenStorageInterface $tokenStorage)
    {
        $this->recordsService = $recordService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function records(): JsonResponse
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $records = $this->recordsService->getRecords($user->getId());

        return new JsonResponse($this->serialize($records), 200);
    }

    protected function serialize(ListRecordsDTO $records)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($records, 'json');
        return $json;
    }
}
