<?php

namespace App\Request\ParamConverter;

use App\Entity\MachineOutil;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * class ItemConverter
 */
class ItemConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * ItemConverter constructor
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if(!$request->attributes->has("id")){
            return;
        }

        $object = $this->entityManager
                        ->getRepository($configuration->getClass())
                        ->find($request->attributes->get("id"));

        $request->attributes->set($configuration->getName(), $object);
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === MachineOutil::class;
    }
}