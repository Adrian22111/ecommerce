<?php

namespace App\Controller\Admin;

use DateTime;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use App\Form\Admin\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

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

            $image = $form->get('image')->getData();

            if ($image) {

                $uploadResult = $this->fileUploader->upload($image, $this->getParameter('product_directory'));

                if(!empty($uploadResult) && $uploadResult->isSuccess()){
                    $productImage = new ProductImage();
                    $productImage->setName($uploadResult->getFileName());
                    $product->addProductImage($productImage);
                } else {
                    $this->addFlash('error', $uploadResult->getMessage());
                    return $this->redirectToRoute('admin_product_edit', [
                        'id' => $product->getId()
                    ], Response::HTTP_SEE_OTHER);
                }
            }

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
}
