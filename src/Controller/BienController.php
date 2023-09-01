<?php

namespace App\Controller;

use App\Repository\BienRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bien', name: 'app_bien')]
class BienController extends AbstractController
{

    #[Route('/', name: '_lister')]
    public function index(BienRepository $bienRepository): Response
    {

        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll()
        ]);
    }

    #[Route('/{id}', name:'_voir', requirements: ['id' => '\d+'])]
    public function voir(BienRepository $bienRepository, $id):Response{
        return $this->render('bien/voir.html.twig', [
        'bien' => $bienRepository->find($id)]);

    }

}
