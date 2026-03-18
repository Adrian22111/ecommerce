<?php

namespace App\Controller\Admin;

use DateTime;
use DateTimeImmutable;
use App\Entity\Product;
use App\Service\ProductImageService;
use App\Entity\ProductImage;
use App\Form\Admin\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/product')]
final class ProductController extends AbstractController
{

    public function __construct()
    {

    }

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


    #[Route('/{id}/upload-image', name: 'product_upload_image', methods: ['POST'])]
    public function uploadProductImage(
        Product $product,
        Request $request,
        ProductImageService $productImageService,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
    ): Response {
        if (!$this->isCsrfTokenValid('upload_product_image' . $product->getId(), $request->headers->get('X-CSRF-TOKEN'))) {
            return new Response($translator->trans('invalid_csrf', [], 'error'), 403);
        }

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
            return new JsonResponse([
                'success' => true,
                'message' => $res->getMessage(),
                'imagePath' => $productImageService->getThumbnailPath($res->getFileName(), 'image_panel_thumbnail_small'),
                'databaseId' => $res->getDatabaseId(),
                'deleteUrl' => $urlGenerator->generate('admin_product_remove_image', [
                    'id' => $product->getId(),
                    'productImage' => $res->getDatabaseId()
                ]),
            ], 200);
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => $res->getMessage()
            ], 400);
        }
    }

    #[Route('/{id}/remove-image/{productImage}', name: 'product_remove_image', methods: ['DELETE'])]
    public function removeProductImage(
        Product $product,
        ProductImage $productImage,
        ProductImageService $productImageService,
        TranslatorInterface $translator,
        Request $request,
    ): Response {
        if (!$this->isCsrfTokenValid('delete_product_image' . $product->getId() , $request->headers->get('X-CSRF-TOKEN'))) {
            return new Response($translator->trans('invalid_csrf', [], 'error'), 403);
        }

        $result = $productImageService->removeImageFromProduct($product, $productImage);

        if ($result) {
            return new Response($translator->trans('image_remove_success', [], 'admin.product'), 200);
        } else {
            return new Response($translator->trans('image_remove_error', [],'admin.product'), 400);
        }
    }

    #[Route('/{id}/images', name: 'product_images', methods: ['GET'])]
    public function getProductImages(Product $product, ProductImageService $productImageService, UrlGeneratorInterface $urlGenerator): Response
    {
        $images = [];
        $productImages = $product->getProductImages();

        foreach ($productImages as $image) {
            $images[] = [
                'id' => $image->getId(),
                'name' => $image->getName(),
                'src' =>  $productImageService->getThumbnailPath($image, 'image_panel_thumbnail_small'),
                'deleteUrl' => $urlGenerator->generate('admin_product_remove_image', [
                    'id' => $product->getId(),
                    'productImage' => $image->getId(),
                ]),
            ];
        }

        return new JsonResponse($images, 200);
    }
}
