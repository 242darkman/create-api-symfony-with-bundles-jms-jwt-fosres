<?php

namespace App\Request\ParamConverter;

use App\Entity\MachineOutil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

/**
 * class PutConverter
 */
class PutConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
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
        if(!$request->getContent(Request::METHOD_PUT)){
            return;
        }

        $object = $request->attributes->get($configuration->getName());

        $new_object = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');

        $object->setName($new_object->getName());
        $object->setDescription($new_object->getDescription());

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