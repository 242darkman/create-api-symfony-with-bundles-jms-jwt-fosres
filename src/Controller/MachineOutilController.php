<?php

namespace App\Controller;

use App\Entity\MachineOutil;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MachineOutilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializationContext as Context;
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
     * liste toutes les machines présentent dans la base de données
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
            $this->serialize($machineRep->findAll(), Context::create()->setGroups(array('list'))),
            Response::HTTP_OK,
            [],
            true);
    }

    /**
     * liste toutes les machines de l'utilisateur connecté
     *
     * @Rest\Get(
     *     path = "/all_user_machines",
     *     name = "user_list_machines_collections_get"
     * )
     * @param MachineOutilRepository $machineRep
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getAllUserMachines(MachineOutilRepository $machineRep, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());
        $all_user_machines = $machineRep->findBy(['user' => $user->getSalt()]);

        return new JsonResponse(
            $this->serialize($all_user_machines, Context::create()->setGroups(array('list'))),
            Response::HTTP_OK,
            [],
        true);
    }

    /**
     * méthode récupérant la machine d'identifiant id
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
            $this->serialize($machineOutil, Context::create()->setGroups(array('list','detail'))),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * création d'une machine
     *
     * @Rest\Post(
     *     path = "/create_machine",
     *     name = "item_machine_create"
     *)
     * @param MachineOutil $machineOutil
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function createMachine(
        MachineOutil $machineOutil,
        UserRepository $userRepository,
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
        $machineOutil->setUser($userRepository->loadUserByUsername($this->getUser()->getUsername()));
        $entityManager->persist($machineOutil);
        $entityManager->flush();

        return new JsonResponse(
            $this->serialize($machineOutil, Context::create()->setGroups(array('list','detail'))),
            Response::HTTP_CREATED,
            ["Location" => $urlGenerator->generate(
                "item_machine_get",
                ["id" => $machineOutil->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
            true
        );
    }

    /**
     * mise à jour de toutes les données de la machine
     * @Rest\Put(
     *     path = "update_data_machine/{id}",
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
        if($machineOutil->getUser() !== $this->getUser()){
            $failure_message = [
                "code" => 403,
                "message" => 'You are not authorized to modify this machine because it is not your'
            ];
            return new JsonResponse($failure_message, Response::HTTP_FORBIDDEN);
        }
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

        $success_msg = [
            "code" => 200,
            "message" => "The update was completed successfully"
        ];
        return new JsonResponse($success_msg, Response::HTTP_OK);
    }

    /**
     * suppression de la machine d'identifiant id
     * @Rest\Delete(
     *     path = "/delete_machine/{id}",
     *     name = "item_machine_delete",
     *     requirements = {"id" = "\d+"}
     * )
     * @param MachineOutil $machineOutil
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteMachine(MachineOutil $machineOutil, EntityManagerInterface $entityManager): JsonResponse
    {
        if($machineOutil->getUser() !== $this->getUser()){
            $failure_message = [
                "code" => 403,
                "message" => 'You are not authorized to delete this machine because it is not your'
            ];
            return new JsonResponse($failure_message, Response::HTTP_FORBIDDEN);
        }
        $entityManager->remove($machineOutil);
        $entityManager->flush();

        $success_message = [
            "code" => 200,
            "message" => 'The machine was successfully deleted'
        ];
        return new JsonResponse($success_message, Response::HTTP_OK);
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
