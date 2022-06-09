<?php

namespace App\Controller;

use App\Entity\MachineOutil;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MachineOutilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * class MachineOutilController
 */
class MachineOutilController extends AbstractFOSRestController
{

    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /**
     * @Rest\Get(
     *     name = "home_api",
     * )
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = [
            "App name " => "DURAND SAS API",
            "City " => "Lyon"
        ];
        return  new JsonResponse($this->serialize($data), Response::HTTP_OK, [], true);
    }

    /**
     * liste toutes nos machines
     *
     * @Rest\Get(
     *     path = "/machines",
     *     name = "list_machines_collections_get"
     * )
     * @param MachineOutilRepository $machineRep
     * @return JsonResponse
     */
    public function getAllMachines(MachineOutilRepository $machineRep): JsonResponse
    {
        return new JsonResponse(
            $this->serialize($machineRep->findAll(), SerializerInterface::create()->setGroups(array('list'))),
            Response::HTTP_OK,
            [],
        true);
    }

    /**
     * méthode récupérant une machine
     * @Rest\Get(
     *     path = "machines/{id}",
     *     name = "item_machine_get",
     *     requirements = {"id"="\d+"}
     * )
     * @param MachineOutil $machineOutil
     * @return JsonResponse
     */
    public function getMachineItem(MachineOutil $machineOutil): JsonResponse
    {
        return new JsonResponse(
            $this->serialize($machineOutil, SerializerInterface::create()->setGroups(array('list','detail'))),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Rest\Post(
     *     path = "/machines",
     *     name = "item_machine_create"
     *)
     * @param MachineOutil $machineOutil
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function createMachine(
        MachineOutil $machineOutil,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $errors = $validator->validate($machineOutil);

        if($errors->count() > 0){
            return new JsonResponse(
                $this->serialize($errors),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }
        $machineOutil->setUser($entityManager->getRepository(User::class)->findOneBy([]));
        $entityManager->persist($machineOutil);
        $entityManager->flush();

        return new JsonResponse(
            $this->serialize($machineOutil, SerializerInterface::create()->setGroups(array('list','detail'))),
            Response::HTTP_CREATED,
            ["Location" => $urlGenerator->generate(
                "item_machine_get",
                ["id" => $machineOutil->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
            true
        );
    }

    /**
     * @Rest\Put(
     *     path = "machines/{id}",
     *     name = "item_machine_update",
     *     requirements = {"id"="\d+"}
     * )
     * @param MachineOutil $machineOutil
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateMachine(
        MachineOutil $machineOutil,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $errors = $validator->validate($machineOutil);

        if($errors->count() > 0){
            return new JsonResponse(
                $this->serialize($errors),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Delete(
     *     path = "/machines/{id}",
     *     name = "item_machine_delete",
     *     requirements = {"id" = "\d+"}
     * )
     * @param MachineOutil $machineOutil
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteMachine(MachineOutil $machineOutil, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($machineOutil);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * sérialize les données
     * @param $data
     * @param $set_groups
     * @return string
     */
    public function serialize($data, $set_groups = null): string
    {
        return $this->serializer->serialize($data, 'json', $set_groups);
    }

    /**
     * désérialize les données
     * @param $data
     * @param null $typeEntity
     * @param null $context
     * @return mixed
     */
    public function deserialize($data, $typeEntity = null, $context = null)
    {
        return $this->serializer->deserialize($data, $typeEntity, 'json', $context);
    }

}
