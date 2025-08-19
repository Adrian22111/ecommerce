<?php

namespace App\Controller\Admin;

use DateTime;
use DateTimeImmutable;
use App\Entity\ProductCategory;
use App\Form\Admin\ProductCategoryForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product_category')]
final class ProductCategoryController extends AbstractController
{
    #[Route(name: 'product_category_list', methods: ['GET'])]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('admin/product_category/index.html.twig', [
            'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryForm::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productCategory->setAddDate(new DateTimeImmutable());
            $productCategory->setLastUpdate(new DateTime());

            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product_category/new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductCategoryForm::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_category_delete', methods: ['POST'])]
    public function delete(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($productCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_product_category_list', [], Response::HTTP_SEE_OTHER);
    }
}
