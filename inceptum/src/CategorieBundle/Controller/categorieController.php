<?php

namespace CategorieBundle\Controller;

use CategorieBundle\Entity\categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 */
class categorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('CategorieBundle:categorie')->findAll();

        return $this->render('categorie/CategoriesFront.html.twig', array(
            'categories' => $categories,
        ));
    }


    /**
     * Finds and displays a categorie entity.
     *
     */
    public function showAction(categorie $categorie)
    {


        return $this->render('categorie/CategorieDetails.html.twig', array(
            'categorie' => $categorie,
        ));
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     */
    public function editAction(Request $request, categorie $categorie)
    {

        $editForm = $this->createForm('CategorieBundle\Form\categorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
        }

        return $this->render('categorie/edit.html.twig', array(
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
        
        ));
    }

    /**
     * Deletes a categorie entity.
     *
     */
    public function deleteAction(Request $request, categorie $categorie)
    {

        if ($categorie) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_indexB');
    }

    /**
     * Lists all categorie entities back.
     *
     */
    public function indexBAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();



        $categorie = new Categorie();
        $categorie->setDateAjout(new \DateTime());
        $form = $this->createForm('CategorieBundle\Form\categorieType', $categorie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
        }

        $categories = $em->getRepository('CategorieBundle:categorie')->findAll();

            return $this->render('categorie/CategoriesBack.html.twig', array(
            'categories' => $categories,
            'categorie' => $categorie,
            'form' => $form->createView(),
        ));
    }


}
