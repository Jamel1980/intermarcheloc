<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\MembreRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class VehiculeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(VehiculeRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $vehicules = $repo->findAll();

        return $this->render('vehicule/index.html.twig', [
            
            'vehicules' => $vehicules
        ]);

        
    }
        #[Route('/view/{id}', name:'view')]
        public function view(Vehicule $vehicule, Request $request, EntityManagerInterface $manager): Response
        {
            if($vehicule == null)
        {
            return $this->redirectToRoute('home');
        }

        if($this->getUser())
        {
            $membre = $this->getUser();
        }else{
            $this->addFlash('success', 'Connectez vous ou Inscrivez vous pour réserver!');
            return $this->redirectToRoute('home'); 
        }
        

        $commande = new Commande;

        $formCommande = $this->createForm(CommandeType::class, $commande);
        $formCommande->handleRequest($request);

        if($formCommande->isSubmitted() && $formCommande->isValid())
        {
            
            $dateDepart = $commande->getDateHeureDepart();
            $dateFin    = $commande->getDateHeureFin();
            $nombreJour = date_diff($dateDepart,$dateFin);

            $prixtotal  = $vehicule->getPrixJournalier()*$nombreJour->d;

            $commande   ->setDateEnregistrement(new \DateTime())
                        ->setMembre($membre)
                        ->setVehicule($vehicule)
                        ->setPrixTotal($prixtotal);

            $manager->persist($commande);
            $manager->flush();
        
            $this->addFlash('success', 'Votre commande a été bien enregistré');

            return $this->redirectToRoute('recap', [
                'id' => $commande->getId()
            ]);      
        }
            return $this->render('vehicule/view.html.twig',[
                'formCommande' => $formCommande->createView(),
                'vehicule' => $vehicule
            ]);
        }

        #[Route('/recap/{id}' , name:'recap')]
        public function recap(Commande $commande): Response
        {
            if(!$this->getUser())
            {
                return $this->redirectToRoute('home');  
            }


            return $this->render('vehicule/commandeMembre.html.twig',[
                'commande' => $commande
            ]);
        }

          #[Route('/compte', name: 'monCompte')]
    public function monCompte(CommandeRepository $repo, MembreRepository $membreRepo): Response
    {
        // Vérifiez que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer les commandes de l'utilisateur connecté
        $commandes = $repo->findBy(['membre' => $user]);

        return $this->render('vehicule/historique.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    

    
}
