<?php

namespace App\Controller\Admin;

use App\Entity\FoodTruck;
use App\Form\FoodTruckType;
use App\Repository\FoodTruckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/food_truck', name: 'app_admin_foodtruck')]
class AdminFoodTruckController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(FoodTruckRepository $foodTruckRepository): Response
    {
        $foodtrucks = $foodTruckRepository->findAll();
        return $this->render('admin/admin_food_truck/index.html.twig', [
            'foodtrucks' =>$foodtrucks,
        ]);
    }
    #[Route ('/ajouter', name: '_ajouter')]
    #[Route ('/modifier/{id}', name: '_modifier')]
    public function editer(Request $request, EntityManagerInterface $entityManager,FoodTruckRepository $foodTruckRepository, int $id = null): Response
    {
        if($id == null){
            $foodtruck = new FoodTruck();
        }else{
            $foodtruck = $foodTruckRepository->find($id);
        }
        $form = $this->createForm(FoodTruckType::class, $foodtruck);
        $form->handleRequest($request);

        //si le form est valide
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($foodtruck); //sauvegarder le bien
            $entityManager->flush(); //l'instruction finale mettant à jour la base de donnéd
            if($id == null) {
                $this->addFlash('success', 'Le foodtruck ' . ($id == null) . ' a été ajouté');
            }else{
                $this->addFlash('success', 'Le foodtruck' . ($id) . ' a été modifié');
            }
            return $this->redirectToRoute('app_admin_foodtruck_lister');
        }

        return $this->render('admin/admin_food_truck/editerFoodTruck.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route ('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(Request $request, EntityManagerInterface $entityManager,FoodTruckRepository $foodTruckRepository,int $id):Response{
        $foodtruck = $foodTruckRepository->find($id);
        $entityManager->remove($foodtruck);
        $entityManager->flush();
        $this->addFlash('danger', 'Le foodtruck ' .($id). ' a été supprimé avec succés');
        return $this->redirectToRoute('app_admin_foodtruck_lister');
    }
}
