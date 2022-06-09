<?php

namespace App\Request\ParamConverter;

use App\Entity\MachineOutil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

/**
 * class PostConverter
 */
class PostConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * PostConverter constructor
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if(!$request->isMethod(Request::METHOD_POST)){
            return;
        }

        $object  = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');

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