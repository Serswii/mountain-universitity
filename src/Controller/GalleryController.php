<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/gallery')]
class GalleryController extends AbstractController
{
    #[Route('/', name: 'gallery_index', methods: ['GET'])]
    public function index(GalleryRepository $galleryRepository, UserInterface $user): Response
    {
        $id_user = $user->getId();
        return $this->render('gallery/index.html.twig', [
            'galleries' => $galleryRepository->findBy(['id_user' => $id_user]),
        ]);
    }

    #[Route('/new', name: 'gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserInterface $user, EntityManagerInterface $entityManager, FileManagerServiceInterface $fileManagerService): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('name_photo')->getData();
            if($image){
                $fileName = $fileManagerService->imagePostUpload($image);
                $gallery->setNamePhoto($fileName);
            }
            $id_user = $user->getId();
            $gallery->setIdUser($id_user);
            $entityManager->persist($gallery);
            $entityManager->flush();
            $this->addFlash('success', 'Фотография добавлена');
            return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'gallery_show', methods: ['GET'])]
    public function show(Gallery $gallery): Response
    {
        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery, EntityManagerInterface $entityManager, FileManagerServiceInterface $fileManagerService): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('name_photo')->getData();
            if($image){
                $fileName = $fileManagerService->imagePostUpload($image);
                $fileManagerService->removePostImage($gallery->getNamePhoto());
                $gallery->setNamePhoto($fileName);
            }
            $entityManager->flush();

            return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery, EntityManagerInterface $entityManager, FileManagerServiceInterface $fileManagerService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $image = $gallery->getNamePhoto();
            if ($image){
                $fileManagerService->removePostImage($image);
                $entityManager->remove($gallery);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
    }
}
