<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use App\Entity\Proveidors;
use App\Form\ProveidorsType;
use App\Form\BotoConfirmarType; 
use App\Form\WhoType;


class ProveidorsController extends AbstractController
{
    /**
     * @Route("/proveidors", name="app_proveidors")
     */
    public function index(): Response
    {
        return $this->render('proveidors/index.html.twig', [
            'titol' => 'ProveidorsController',
        ]);
    }

    /**
     * @Route("/proveidors/add", name="add_proveidors")
     */
    public function add(ManagerRegistry $manager, Request $request): Response
    {
        $entityManager = $manager->getManager();
        $proveidor = new Proveidors();

        #Dades que ha de modificar l'aplicació
        $proveidor->setActiu(true);
        $proveidor->setAlta(new \DateTime('now'));
        $proveidor->setEdicio(new \DateTime('now'));

        $form = $this->createForm(ProveidorsType::class,$proveidor);
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proveidor); #Persistencia
            $entityManager->flush();        #Query
            return $this->redirect("http://localhost/Practica_Symfony/public/proveidors/");
        }

        return $this->render('proveidors/add.html.twig', [
            'titol' => 'Afegir proveidor', 'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/proveidors/edit/{id}", name="edit_proveidors")
     */
    public function edit(ManagerRegistry $manager, Request $request, int $id): Response
    {   
        $entityManager = $manager->getManager();
        $proveidor = $entityManager->getRepository(Proveidors::class)->find($id);
        
        if (!$proveidor) {
            return $this->render('proveidors/error.html.twig', [
                #envia la variable titol amb el contingut user template
                'titol' => "No s'ha trobat cap proveidor amb ID $id"
            ]); 
        }
        
        $proveidor->setEdicio(new \DateTime('now')); #Actualitza data d'edició 
        $form = $this->createForm(ProveidorsType::class,$proveidor);
        
        #Emmagatzema a l'objecte form les dades del formulari
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirect("http://localhost/Practica_Symfony/public/proveidors/"); 
        }

        return $this->render('proveidors/edit.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => "Edicio de proveidor amb ID $id", 'form' => $form->createView(),
        ]);        
    }

    /**
     * @Route("/proveidors/edit", name="editWho_proveidors")
     */
    public function editWho(ManagerRegistry $manager, Request $request): Response
    {   
        $entityManager = $manager->getManager();

        $form = $this->createForm(WhoType::class);
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form["ID"]->getData();
            return $this->redirect("http://localhost/Practica_Symfony/public/proveidors/edit/$id");              
        }

        return $this->render('proveidors/editWho.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Quin proveidor vols editar?', 'form' => $form->createView(),
        ]);        
    }



    /**
     * @Route("/proveidors/remove/{id}", name="delete_proveidors")
     */
    public function remove(ManagerRegistry $manager, Request $request, int $id): Response
    {
        $entityManager = $manager->getManager();
        $proveidor = $entityManager->getRepository(Proveidors::class)->find($id);
        $id = $proveidor->getId();

        if (!$proveidor) {
            /*throw $this->createNotFoundException(
                'No hem trobat cap proveidor amb id '.$id
            );*/
            return $this->render('proveidors/error.html.twig', [
                #envia la variable titol amb el contingut user template
                'titol' => "No s'ha trobat cap proveidor amb ID $id"
            ]); 
        }

        $form = $this->createForm(BotoConfirmarType::class);
        $form->handleRequest($request);

        if (($form->isSubmitted())){
            $entityManager->remove($proveidor);
            $entityManager->flush();
            $proveidor_check = $entityManager->getRepository(Proveidors::class)->find($id);
            if($proveidor_check){
                throw $this->createNotFoundException(
                    'Error: No sha pogut eliminar lusuari '.$id
                );
            }
            return $this->redirect("http://localhost/Practica_Symfony/public/proveidors/");    
        }

        return $this->render('proveidors/delete.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => "Eliminant el proveidor amb ID $id", 'form' => $form->createView(), 'proveidor' => $proveidor
        ]); 
    }

    /**
     * @Route("/proveidors/remove", name="removeWho_proveidors")
     */
    public function removeWho(ManagerRegistry $manager, Request $request): Response
    {   
        $entityManager = $manager->getManager();

        $form = $this->createForm(WhoType::class);
        $form->handleRequest($request);

        #Si (Botó de save usat i dades vàlides)
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form["ID"]->getData();
            return $this->redirect("http://localhost/Practica_Symfony/public/proveidors/remove/$id");              
        }

        return $this->render('proveidors/editWho.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Quin proveidor vols eliminar?', 'form' => $form->createView(),
        ]);        
    }

    

    /**
     * @Route("/proveidors/read", name="read_proveidors")
     */
    public function read(ManagerRegistry $doctrine): Response
    {
        #Amb aquest objecte cridem als mètodes de la entitat user
        $proveidors_rep = $doctrine->getRepository(Proveidors::class);
        
        $dades = $proveidors_rep->findAll();
        return $this->render('proveidors/read.html.twig', [
            #envia la variable titol amb el contingut user template
            'titol' => 'Mostrant les dades dels proveidors', 'dades' => $dades,
        ]);
    }



}
