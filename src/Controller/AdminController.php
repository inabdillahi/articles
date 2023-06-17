<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategorieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    #[Route('/utilisateur/admin', name: 'admin')]
    public function adminHome(AnnoncesRepository $repo): Response
    {
        $annonces = $repo->findAll();
        return $this->render('admin/admin.html.twig', [
            'annonces' => $annonces
        ]);
    }

    #[Route('/utilisateur/admin/annonces/supprimer/{id}', name: 'admin-annonces-delete')]
    public function annoncesDelete($id, AnnoncesRepository $repo, EntityManagerInterface $manager)
    {
        $anonnce = $repo->find($id);
        $manager->remove($anonnce);
        $manager->flush();
        $this->addFlash('success', 'Vous avez bien supprimer une annonces');
        return $this->redirectToRoute('admin');
    }



    #[Route('/utilisateur/admin/categorie', name: 'categorie')]
    #[Route('/utilisateur/admin/categorie/edit/{id}', name: 'modifier-categorie')]
    public function categorie(Categorie $categorie, CategorieRepository $repo, Request $request, EntityManagerInterface $manager, SluggerInterface $slug): Response
    {
        if ($categorie == null) {
            $categorie = new Categorie;
        }
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setSlug(strtolower($slug->slug($categorie->getNom())));
            $manager->persist($categorie);
            $manager->flush();
            return $this->redirectToRoute('categorie');
        }

        //Affichage
        $categories = $repo->findAll();
        return $this->render('admin/categorie.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }



    #[Route('/utilisateur/admin/categorie/supprimer/{id}', name: 'categorie-delete')]
    public function categorieDelete($id, CategorieRepository $repo, EntityManagerInterface $manager)
    {
        $categorie = $repo->find($id);
        $manager->remove($categorie);
        $manager->flush();
        $this->addFlash('success', 'Vous avez bien supprimer le categorie');
        return $this->redirectToRoute('categorie');
    }




    #[Route('/utilisateur/admin/utilisateur', name: 'utilisateur')]
    public function utilisateur(EntityManagerInterface $manager, UtilisateurRepository $repo): Response
    {
        $user = $repo->findAll();
        
        return $this->render('admin/user.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/utilisateur/admin/utilisateur/supprimer/{id}', name: 'utilisateur-delete')]
    public function utilisateurdelete($id, EntityManagerInterface $manager, UtilisateurRepository $repo)
    {
        $user = $repo->find($id);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Vous avez bien supprimer Un utilisateur');
        return $this->redirectToRoute('utilisateur');
    }
}
