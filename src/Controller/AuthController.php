<?php

namespace App\Controller;

use App\Encoder\APIJWTEncoder;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthController extends AbstractController
{

    /**
     * @var SerializerInterface
     */
    public SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * méthode créant le compte d'un utilisateur dans notre API
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $request_content = json_decode($request->getContent(), true);
        $name = $request_content['name'];
        $mail = $request_content['email'];
        $password = $request_content['password'];

        if(empty($name) || empty($mail) || empty($password)){
            $error_message = [
                "code" => 422,
                "message" => "Invalid Name or Password or Email"
            ];
            return new JsonResponse($error_message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create($mail, $name);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        $success_message = [
            "code" => "200",
            "message" => sprintf('User %s successfully created', $user->getName())
        ];

        return new JsonResponse($success_message, Response::HTTP_CREATED);
    }

    /**
     * méthode permettant l'authentification d'un utilisateur sur notre API
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     * @throws JWTEncodeFailureException
     */
    public function login(Request $request, UserRepository $repository, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $request_content = json_decode($request->getContent(), true);
        $user = $repository->findOneBy(['mail'=>$request_content['email'],]);

        if(!$user || !$encoder->isPasswordValid($user, $request_content['password']))
        {
            $failure_message = [
                "code" => 422,
                "message" => 'email or password is wrong'
            ];
            return new JsonResponse($failure_message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $payload = [
            'username' => $user->getUsername(),
            'exp' => time() + 3600, // 1 hour expiration
        ];

        $api_encoder = new APIJWTEncoder();
        $token = $api_encoder->encode($payload); // génération de token

        $success_msg = [
            "code" => 200,
            "message"  => sprintf('User %s logged with success', $user->getUsername()),
            "token" => sprintf('Bearer %s ', $token)
        ];

        return new JsonResponse($success_msg, Response::HTTP_OK);
    }


}
