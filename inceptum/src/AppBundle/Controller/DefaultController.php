<?php

namespace AppBundle\Controller;

use ProduitBundle\Entity\produit;
use AppBundle\Entity\commande;
use AppBundle\Entity\prodCom;
use CategorieBundle\Entity\categorie;
use mysql_xdevapi\Session;
use ProduitBundle\Repository\produitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{




    /**
     * @Route("/", name="homepage")
     */
    public function indexAccAction()
    {

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $sql = 'SELECT * FROM produit ORDER BY dateAjout DESC LIMIT 3';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $array = $stmt->fetchAll();

        $authChecker = $this->container->get('security.authorization_checker');


        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('default/accueilBack.html.twig');
        } else {
            return $this->render('default/accueil.html.twig', array(
                'produits' => $array,
            ));
        }
    }





    /**
    * @Route("/commande", name="commande")
    */

    public function indexPasserComAction(SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('ProduitBundle:produit')->findAll();
        $entityManager = $this->getDoctrine()->getManager();

        $commande = new commande();
        $user = $this->getUser();
        $commande->setNomProprietaire($user->getUsername());
        $commande->setEmailProprietaire($user->getEmail());
        $commande->setIdClient($user->getId());
        $commande->setDateAjout(new \DateTime());
        $commande->setEtat('En attente');
        $commande->setTelProprietaire($user->getTelephone());
        $commande->setAddProprietaire($user->getAddresse());
        $entityManager->persist($commande);
        $entityManager->flush();


        $idCommande = $commande->getId();


        $panier = $session->get('panier', []);
        $panierWithData = [];

        $em = $this->getDoctrine()->getManager();
        foreach ($panier as $id => $quantite)
        {

            $panierWithData[] = [
                'produit'=> $em->getRepository('ProduitBundle:produit')->find($id),
                'quantite'=>$quantite
            ];
        }



        foreach ($panierWithData as $item)
        {
            $prodCom = new prodCom ();
            $prodCom->setNomProduit($item['produit']->getNomP());
            $prodCom->setQuantiteProduit($item['quantite']);
            $prodCom->setIdCommande($idCommande);
            $entityManager->persist($prodCom);
            $entityManager->flush();

        }

        $session->clear();



        return $this->render('default/accueil.html.twig', array(
                'produits' => $produits,
            ));

    }


    /**
     * @Route("/listeCommandes", name="listeCommandes")
     */

    public function listeCommandesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository('AppBundle:commande')->findAll();

        return $this->render('produit/CommandeBack.html.twig', array(
            'commandes' => $commandes,
        ));
    }














}
