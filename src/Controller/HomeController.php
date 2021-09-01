<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Crud;
use App\Form\CrudType;
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request){
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form-> handleRequest($request);
if($form->isSubmitted() && $form->isValid()){
    $em = $this->getDoctrine()->getManager();
    $em->persist($crud);
    $em->flush();
    $this->addFlash('motice','Submit Successfully!!');
    

    return $this->redirectToRoute('main');
}
        return $this->render('main/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
