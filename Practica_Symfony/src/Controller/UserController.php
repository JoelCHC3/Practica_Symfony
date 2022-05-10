<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User; //Llibreria d'usuari
#use Doctrine\Persistence\ObjectManager; //Manegador de les operacions
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserType; //Importo el formulari

class UserController extends AbstractController
{
    /**
     * @Route("/read", name="llegint_BD")
     */
    //Retorna la info del fitxer indicat.
    public function read(ManagerRegistry $doctrine): Response
    {
        #Amb aquest objecte cridem als mètodes de la entitat user
        $user_repository = $doctrine->getRepository(User::class);
        
        #$users sera un array d'arrays amb la info dels usuaris
        $users = $user_repository->findAll();
        
        #Informació d'un usuari segons la seva ID
        $userById = $user_repository->find(3);
        
        #Extraiem els usuaris segons el camp Img
        $userBy = $user_repository->findByImg('default.jpg');

        #Extrau el primer usuari que troba segons el camp Img
        $userOneBy = $user_repository->findOneByImg('default.jpg');

        #var_dump($users); #Mostra els usuaris
        #var_dump($userOneBy); #Mostra l'usuari consultat
        #die();
        return $this->render('proves/read_BD.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Llegint dades de la BD', 'users' => $users,
            'userById' => $userById, 'userBy' => $userBy,
            'userOneBy' => $userOneBy
        ]);
    }

    /**
     * @Route("/", name="inici")
     */
    //Amb aquesta funció es crearan nous usuaris
    public function inici(ManagerRegistry $manager, Request $request): Response
    {   
        $entityManager = $manager->getManager();
        //Instancio l'objecte user. Amb ell cridarem els metodes de la entitat
        $user = new User();

        $user->setRoles(['ROLE_USER']);
        $user->setImg('default.jpg');
        $user->setActive(true);
        //$user->setData(new \DateTime('now')); Ingressa la data i hora de registre
        $form = $this->createForm(UserType::class,$user); //Demanarà el nom de la classe del formulari i la classe de la entitat.
        
        #Emmagatzema a l'objecte form les dades del formulari
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            #Indica que es vol persistència de les dades (encara no fa query)
            $entityManager->persist($user);
            #Executa la query per inserir les dades
            $entityManager->flush();
        }


        return $this->render('inici/inici.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Inici template', 'form' => $form->createView(),
        ]);       
    }

    /**
     * @Route("/user/edit/{id}", name="edit")
     */
    //Amb aquesta funció es crearan nous usuaris
    public function edit(ManagerRegistry $manager, Request $request, int $id): Response
    {   
        $entityManager = $manager->getManager();
        //Instancio l'objecte user. Amb ell cridarem els metodes de la entitat
        #$user = new User();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        
        //$user->setData(new \DateTime('now')); Ingressa la data i hora de registre
        $form = $this->createForm(UserType::class,$user); //Demanarà el nom de la classe del formulari i la classe de la entitat.
        
        #Emmagatzema a l'objecte form les dades del formulari
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            #Indica que es vol persistència de les dades (encara no fa query)
            #$entityManager->persist($user);
            #Executa la query per inserir les dades
            $entityManager->flush();
        }

        return $this->render('edit/edit.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Edit template', 'form' => $form->createView(),
        ]);

        
    }

    /**
     * @Route("/user/remove/{id}", name="delete")
     */
    //Retorna la info del fitxer indicat.
    public function remove(ManagerRegistry $manager, Request $request, int $id): Response
    {
        $entityManager = $manager->getManager();
        //Instancio l'objecte user. Amb ell cridarem els metodes de la entitat
        #$user = new User();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $user_check = $entityManager->getRepository(User::class)->find($id);

        if($user_check){
            throw $this->createNotFoundException(
                'No sha eliminat lusuari '.$id
            );
        }

        return $this->render('remove/remove.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Usuari eliminat correctament',
        ]);

        
    }


    /**
     * @Route("/user", name="app_user")
     */
    //Retorna la info del fitxer indicat.
    public function user(): Response
    {
        return $this->render('user/user.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'User template',
        ]);
    }

    
}
