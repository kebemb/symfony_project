<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/bien', name: 'app_admin_bien')]
class AdminBienController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->findAll();
        return $this->render('admin/admin_bien/index.html.twig', [
            'biens' =>$biens,
        ]);
    }


    #[Route ('/ajouter', name: '_ajouter')]
    #[Route ('/modifier/{id}', name: '_modifier')]
    public function editer(Request $request, EntityManagerInterface $entityManager,BienRepository $bienRepository, int $id = null): Response
    {
        if($id == null){
            $bien = new Bien();
        }else{
            $bien = $bienRepository->find($id);
        }
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        //si le form est valide
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($bien); //sauvegarder le bien
            $entityManager->flush(); //l'instruction finale mettant à jour la base de donnéd
            if($id == null) {
                $this->addFlash('success', 'le bien ' . ($id == null) . ' a été ajouté');
            }else{
                $this->addFlash('success', 'le bien ' . ($id) . ' a été modifié');
            }
            return $this->redirectToRoute('app_admin_bien_lister');
        }

        return $this->render('admin/admin_bien/editerBien.html.twig', [
            'form' => $form,
        ]);
    }

#[Route ('/supprimer/{id}', name: '_supprimer')]
public function supprimer(Request $request, EntityManagerInterface $entityManager,BienRepository $bienRepository,int $id):Response{
    $bien = $bienRepository->find($id);
    $entityManager->remove($bien);
    $entityManager->flush();
    $this->addFlash('danger', 'le bien ' .($id). ' a été supprimé avec succés');
    return $this->redirectToRoute('app_admin_bien_lister');
}

}

