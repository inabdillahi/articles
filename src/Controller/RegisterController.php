<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    #[Route('/register/edit/{id}', name: 'register-modifier')]
    public function register(Utilisateur $user, UserPasswordHasherInterface $hash, Request $request, EntityManagerInterface $manager): Response
    {
        if ($user == null) {
            $user = new Utilisateur;
        }
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new \DateTimeImmutable());
            $encode = $hash->hashPassword($user, $user->getPassword());
            $user->setPassword($encode);
            $user->setStatus(true);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vous bien enregistrer');
            return $this->redirectToRoute('register');
        }
        $formv = $form->createView();
        return $this->render('register/register.html.twig', [
            'form' => $formv
        ]);
    }
}
