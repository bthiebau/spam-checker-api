<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class IndexController extends AbstractController
{
    private const SPAM_DOMAINS = [
        'spam.fr',
        'free.fr',
        'test.fr'
        ];
    #[Route('/check', name: 'api_check_email', methods: ["POST"])]
    public function check(Request $request): JsonResponse
    {
        $data = $request->toArray();

        if(!isset ($data['email']) || empty($data['email'])){
            //throw new BadRequestHttpException('L\'email est obligatoire');
            return $this->json(
                ["error" => "L'email est obligatoire"],
                Response::HTTP_BAD_REQUEST
            );
        }

        if(filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false){
            //throw new UnprocessableEntityHttpException('l\'email invalide');
            return $this->json(
                ["error" => "L'email est invalide"],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        $email = $data['email'];

        $parts = explode("@", $email);
        $domain = $parts[1];

        if(in_array($domain, self::SPAM_DOMAINS)){
            return $this->json(['result' => 'spam']);
        }
        return $this->json(['result' => 'ok']);
    }

}
