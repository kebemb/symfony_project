<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Entity\BienUser;
use App\Form\BienType;
use App\Repository\BienRepository;

use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/bien', name: 'app_admin_bien')]
class AdminBienController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(BienRepository $bienRepository): Response
    {

        return $this->render('admin/admin_bien/index.html.twig', [
            'biens' => $bienRepository->findBy(['user' => $this->getUser()
        ])
        ]);
    }


    #[Route ('/ajouter', name: '_ajouter')]
    #[Route ('/modifier/{id}', name: '_modifier')]
    public function editer(Request $request,
                           EntityManagerInterface $entityManager,
                           BienRepository $bienRepository,
                           UploadService $uploadService, int $id = null): Response
    {
        if($id == null){
            $bien = new Bien();
            $bienUser = new BienUser();
            $bien->addBienUser($bienUser);
        }else{
            $bien = $bienRepository->find($id);

            if($bien->getUser() != $this->getUser()){
                $this->addFlash('danger',
                'Pas par ici');
                return $this->redirectToRoute('app_admin_bien_lister');
            }
        }
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        //si le form est valide
        if($form->isSubmitted() && $form->isValid()) {

            $bien->setUser($this->getUser());

            $photoFile = $form->get('photoPrincipal')->getData();
            if ($photoFile) {

                $newFilename = $uploadService->upload($photoFile, $this->getParameter('biens_directory'));

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $bien->setPhotoPrincipal($newFilename);


            }

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
            'bien' =>$bien
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

