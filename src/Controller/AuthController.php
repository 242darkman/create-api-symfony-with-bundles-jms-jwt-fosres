<?php

namespace App\Controller;

use App\Encoder\APIJWTEncoder;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthController extends AbstractController
{

    public SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * méthode créant le compte d'un utilisateur
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        dd($request);
        $em = $this->getDoctrine()->getManager();
        $request = $this->decode($request);
        $name = $request->get('name');
        $mail = $request->get('email');
        $password = $request->get('password');

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
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login(Request $request, UserRepository $repository, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        //dd(json_decode($request->getContent(), true)); OK
        $content = json_decode($request->getContent(), true);
        //dd($content['mail']); OK
        $user = $repository->findOneBy(['mail'=>$content['mail'],]);
        //dd($user); OK
        //dd($encoder->isPasswordValid($user, $content['password'])); OK

        if(!$user || !$encoder->isPasswordValid($user, $content['password']))
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

        //$token = $this->get('lexik_jwt_authentication.encoder')->encode($payload);
        $api_encoder = new APIJWTEncoder();
        $token = $api_encoder->encode($payload);
        $success_msg = [
            "code" => 200,
            "message"  => sprintf('User %s logged with success', $user->getUsername()),
            "token" => sprintf('Bearer %s ', $token)
        ];

        return new JsonResponse($success_msg, Response::HTTP_OK);
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $tokenManager
     * @return JsonResponse
     */
 /*   public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $tokenManager): JsonResponse
    {
        return new JsonResponse(['token' => $tokenManager->create($user)]);
    }*/


}
