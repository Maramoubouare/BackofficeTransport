<?php

namespace App\Controller;

// src/Controller/Api/UserController.php

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UtilisateurRepository;
class ApiuserController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $utilisateurRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UtilisateurRepository $utilisateurRepository)
{
    $this->entityManager = $entityManager;
    $this->passwordHasher = $passwordHasher;
    $this->utilisateurRepository = $utilisateurRepository;
}
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new Utilisateur();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $data['password'])
        );

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse(['error' => $errorsString], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User created!'], JsonResponse::HTTP_CREATED);
    }
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $username = $data['username'];
        $password = $data['password'];
    
        $user = $this->utilisateurRepository->findOneBy(['username' => $username]);
    
        if (!$user) {
            return new JsonResponse(['error' => 'Invalid username'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid password'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Génération d'un token (simulé ici)
        $userToken = 'token123'; // Remplacez par votre logique de génération de token
    
        return new JsonResponse(['userToken' => $userToken, 'username' => $user->getUsername()]);
    }
}    