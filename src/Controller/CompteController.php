<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * Permet de se connecter
     * @Route("/login", name="compte_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error=$utils->getLastAuthenticationError();
        $username=$utils->getLastUsername();
        return $this->render('compte/login.html.twig',[
            'haserror'=>$error!==null,
            'username'=>$username
        ]);
    }
    /**
     * Permet de se deconnecter
     *@Route("/logout",name="compte_logout")
     * 
     * @return void
     */
    public function logout()
    {

    }
    /**
     * Permet de s'inscrire
     *@Route("/register",name="compte_register")
     * 
     * 
     * @return Response
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $manager=$this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success","Inscription effectée. Vous pouvez vous connectez!");
            return $this->redirectToRoute("compte_login");
        }
        return $this->render('compte/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}