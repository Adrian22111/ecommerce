<?php

namespace App\Controller\Admin;

use DateTime;
use DateTimeImmutable;
use App\Entity\Product;
use App\Service\ProductImageService;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use App\Form\Admin\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product')]
final class ProductController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private FileUploader $fileUploader
    ) {}

    #[Route(name: 'product_list', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setAddDate(new DateTimeImmutable());
            $product->setLastUpdate(new DateTime());

            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'save_successfull');

            return $this->redirectToRoute('admin_product_edit', [
                'id' => $product->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Product $product,
        EntityManagerInterface $entityManager,
    ): Response {

        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setLastUpdate(new DateTime());
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'update_successfull');
            return $this->redirectToRoute('admin_product_edit', [
                'id' => $product->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'delete_successfull');
        }

        return $this->redirectToRoute('admin_product_list', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadProductImage(
        Product $product,
        Request $request,
        ProductImageService $productImageService,
        TranslatorInterface $translator

    ): Response {
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile instanceof UploadedFile) {
            return new Response(
                $translator->trans(
                    'no_file_uploaded',
                    [],
                    'admin.product'
                ),
                400
            );
        }

        $res = $productImageService->addImageToProduct($product, $uploadedFile);

        if ($res->isSuccess()) {
            return new Response(
                $res->getMessage(),
                200
            );
        } else {
            return new Response(
                $res->getMessage(),
                400
            );
        }
    }
}
