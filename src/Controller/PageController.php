<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Service\FileManagerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page', name: 'page')]
    public function page(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('main/default.html.twig', []);
    }

    #[Route('/edit-gallery/{id}', name: 'edit_gallery', requirements: ['id' => '\d+'], methods: 'GET|POST')]
    #[Route('/gallery', name: 'upload_gallery', methods: 'GET|POST')]
    public function editProduct(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($id) {
            $gallery = $entityManager->getRepository(Gallery::class)->find($id);
        }
        else {
            $gallery = new Gallery();
        }
        $form = $this->createFormBuilder($gallery)
            ->add('name_photo', TextType::class)
            ->getForm();
       // dd($gallery, $form);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $entityManager->persist($gallery);
            $entityManager->flush();
            $this->addFlash('success', 'Фотография добавлена');
            return $this->redirectToRoute('edit_gallery', ['id' => $gallery->getId()]);
        }
        return $this->render('page/edit_gallery.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/gallery-create', name: 'add_gallery', methods: 'GET|POST')]
    public function create(Request $request, FileManagerServiceInterface $fileManagerService)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $gallery = new Gallery();
        $form = $this->createForm(TextType::class ,$gallery);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('name_photo')->getData();
            if($image){
                $fileName = $fileManagerService->imagePostUpload($image);
                $gallery->setNamePhoto($fileName);
            }
            $id_user = $this->getUser()->getId();
            $gallery->getIdUser($id_user);
            $entityManager->persist($gallery);
            $entityManager->flush();
            $this->addFlash('success', 'Фотография добавлена');
            return $this->redirectToRoute('add_gallery', []);
        }
        dd($form);
        return $this->render('page/add_gallery.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
