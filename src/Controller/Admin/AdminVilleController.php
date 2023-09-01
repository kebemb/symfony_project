<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Entity\Ville;
use App\Form\BienType;
use App\Form\VilleType;
use App\Repository\BienRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ville', name: 'app_admin_ville')]
class AdminVilleController extends AbstractController
{

    #[Route('/', name: '_lister')]
    public function lister(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();
        return $this->render('admin/admin_ville/index.html.twig', [
            'villes' =>$villes,
        ]);
    }
    #[Route ('/ajouter', name: '_ajouter')]
    #[Route ('/modifier/{id}', name: '_modifier')]
    public function editer(Request $request, EntityManagerInterface $entityManager,VilleRepository $villeRepository, int $id = null): Response
    {
        if($id == null){
            $ville = new Ville();
        }else{
            $ville = $villeRepository->find($id);
        }
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        //si le form est valide
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($ville); //sauvegarder le bien
            $entityManager->flush(); //l'instruction finale mettant à jour la base de donnéd
            if($id == null) {
                $this->addFlash('success', 'La ville ' . ($id == null) . ' a été ajoutée');
            }else{
                $this->addFlash('success', 'La ' . ($id) . ' a été modifiée');
            }
            return $this->redirectToRoute('app_admin_ville_lister');
        }

        return $this->render('admin/admin_ville/editerVille.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route ('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(Request $request, EntityManagerInterface $entityManager,VilleRepository $villeRepository,int $id):Response{
        $ville = $villeRepository->find($id);
        $entityManager->remove($ville);
        $entityManager->flush();
        $this->addFlash('danger', 'La ville ' .($id). ' a été supprimé avec succés');
        return $this->redirectToRoute('app_admin_ville_lister');
    }
}
