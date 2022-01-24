<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use App\Service\FileManagerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('main/default.html.twig', [
            'galleries' => $galleryRepository->findAll(),
        ]);
    }
}
