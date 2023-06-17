<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AjouterAnnonceType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesannnoncesController extends AbstractController
{
    #[Route('/utilisateur/mes-annonces', name: 'mesannnonces')]
    public function mesannonces(AnnoncesRepository $repoAnnonces, CategorieRepository $repoo): Response
    {
        $user = $this->getUser();
        $Mesannonces = $repoAnnonces->findBy([
            'utilisateur' => $user
        ]);
        $nombreAnnonces = count($Mesannonces);

        //Categorie
        $categories = $repoo->findAll();
        return $this->render('mesannnonces/mesannonce.html.twig', [
            'mesAnnonces' => $Mesannonces,
            'nombreAnnonce' => $nombreAnnonces,
            'categories' => $categories
        ]);
    }


    #[Route('/utilisateur/mes-annonces/ajouter', name: 'ajouter-annonce')]
    #[Route('/utilisateur/mes-annonces/edit/{id}', name: 'modifier-annonce')]
    public function ajouterUneAnnonce(Annonces $annonce, Request $request, EntityManagerInterface $manager): Response
    {
        if ($annonce == null) {
            $annonce = new Annonces;
        }
        $user = $this->getUser();
        $form = $this->createForm(AjouterAnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setCreatedAt(new \DateTimeImmutable());
            $annonce->setImage('http://picsum.photos/300/300');
            $annonce->setStatus(true);
            $annonce->setUtilisateur($user);
            $manager->persist($annonce);
            $manager->flush();
            $this->addFlash('success', 'Vous avez bien enregistrer une annonce');
            return $this->redirectToRoute('mesannnonces');
        }
        return $this->render('mesannnonces/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/utilisateur/mes-annonces/supprimer/{id}', name: 'annonces-delete')]
    public function mesAnnoncesDelete($id, AnnoncesRepository $repo, EntityManagerInterface $manager)
    {
        $annonce = $repo->find($id);
        $manager->remove($annonce);
        $manager->flush();
        $this->addFlash('success', 'Vous avez bien supprimer une annonce');
        return $this->redirectToRoute('mesannnonces');
    }
}
