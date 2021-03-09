<?php

namespace AppBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
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
        $sqlAdmin = 'SELECT DISTINCT nomProprietaire, COUNT(*) AS commandes FROM commande GROUP BY nomProprietaire';
        $sqlNbCommandes = 'SELECT COUNT(*) AS nbCommandes FROM commande';
        $sqlNbUsers = 'SELECT COUNT(*) AS nbUsers FROM fos_user';
        $sqlNbLivres = 'SELECT COUNT(*) AS nbLivres FROM produit';
        $sqlAdmin2 = 'SELECT nomP,nomCat,COUNT(*) AS toBeUsed FROM produit,categorie WHERE categorie.id = categorie_id GROUP BY nomCat';
        $sqlNbCat = 'SELECT COUNT(*) as nbCat FROM Categorie';
        $stmt = $conn->prepare($sql);
        $stmtAdmin = $conn->prepare($sqlAdmin);
        $stmtNbCommandes = $conn->prepare($sqlNbCommandes);
        $stmtNbUsers = $conn->prepare($sqlNbUsers);
        $stmtNbLivres = $conn->prepare($sqlNbLivres);
        $stmtAdmin2 = $conn->prepare($sqlAdmin2);
        $stmtNbCat = $conn->prepare($sqlNbCat);
        $stmt->execute();
        $stmtAdmin->execute();
        $stmtNbCommandes->execute();
        $stmtNbUsers->execute();
        $stmtNbLivres->execute();
        $stmtAdmin2->execute();
        $stmtNbCat->execute();
        $array = $stmt->fetchAll();
        $arrayAdmin = $stmtAdmin->fetchAll();
        $arrayNbCommandes = $stmtNbCommandes->fetchAll();
        $arrayNbUsers = $stmtNbUsers->fetchAll();
        $arrayNbLivres = $stmtNbLivres->fetchAll();
        $arrayAdmin2 = $stmtAdmin2->fetchAll();
        $arrayNbCat = $stmtNbCat->fetchAll();

        $authChecker = $this->container->get('security.authorization_checker');


        //NUMBER OF COMMANDES
        $nbCommandes = 0;
        foreach ($arrayNbCommandes as $nb){
            $nbCommandes +=  intval($nb['nbCommandes']);
        }

        //NUMBER OF BOOKS
        $nbLivres = 0;
        foreach ($arrayNbLivres as $nb){
            $nbLivres += intval($nb['nbLivres']);
        }

        //NUMBER OF CATEGORIES
        $nbCat = 0;
        foreach ($arrayNbCat as $nb){
            $nbCat += intval($nb['nbCat']);
        }


        //NUMBER OF USERS
        $nbUsers = 0;
        foreach ($arrayNbUsers as $nb){
            $nbUsers += intval($nb['nbUsers']);
        }


        //$data = [['nomProprietaire','commandes']];
        //foreach ($arrayAdmin as $ad){
         //   $data[] = array($ad['nomProprietaire'],$ad['commandes']);
        //}

        //MAKING THE COLUMN CHART

        $data = array(['Utilisateur', 'Nombre de Commandes', ['role' => 'annotation']]);

        foreach ($arrayAdmin as $item) {
            array_push($data,[['v' => $item['nomProprietaire'], 'f' => $item['nomProprietaire']],  intval($item['commandes']), $item['commandes']]);
        }

        $col = new ColumnChart();
        $col->getData()->setArrayToDataTable($data);
        $col->getOptions()->setTitle('Nombre de Commandes en rapport avec les utilisateurs');
        $col->getOptions()->getAnnotations()->setAlwaysOutside(true);
        $col->getOptions()->getAnnotations()->getTextStyle()->setFontSize(14);
        $col->getOptions()->getAnnotations()->getTextStyle()->setColor('#000');
        $col->getOptions()->getAnnotations()->getTextStyle()->setAuraColor('none');
        $col->getOptions()->getHAxis()->setTitle('Utilisateur');

        $col->getOptions()->getVAxis()->setTitle('Nombre Totale de Commandes');
        $col->getOptions()->setWidth(600);
        $col->getOptions()->setHeight(400);

        //MAKING THE PIE CHART

        $data2 = array(['Categorie','Nombre de Livres']);
        foreach ($arrayAdmin2 as $item){
            array_push($data2,[$item['nomCat'],intval($item['toBeUsed'])]);
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($data2);
        $pieChart->getOptions()->setTitle('Pourcentages des Livres pour chaque Catégories');
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->setHeight(400);






        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('default/accueilBack.html.twig', array(
                'colchart' => $col,
                'arrayAdmin' => $arrayAdmin,
                'nbCommandes' => $nbCommandes,
                'nbUsers' => $nbUsers,
                'piechart' => $pieChart,
                'nbLivres' => $nbLivres,
                'nbCat' => $nbCat,
            ));
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
