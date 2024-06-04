<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\ProfileeditType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileeditController extends AbstractController
{
    #[Route('/profileedit', name: 'app_profile_edit')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,MembreRepository $repo): Response
    {
        //Si l'utilisateur n'est pas connecté
        if(!$this->getUser())
        {
            return $this->redirectToRoute('home');  
        }

        $user = $repo->findByEmail($this->getUser()->getUserIdentifier());
       

        $form = $this->createForm(ProfileeditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            if(!empty($form->get('plainPassword')->getData())){
          
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );      
            }

            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setPseudo($form->get('pseudo')->getData());
            

            $entityManager->persist($user);
            $entityManager->flush();

            // Do anything else you need here, like send an email

            return $this->redirectToRoute('monCompte');
        }

        return $this->render('profileedit/index.html.twig', [
            'profileditForm' => $form->createView(),
            'user' => $user 
        ]);
    }
}