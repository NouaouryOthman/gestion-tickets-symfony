<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Role;
use App\Entity\User;
use App\Form\AssignationType;
use App\Form\TicketType;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket/new", name="ticket_create")
     * @IsGranted("ROLE_USER")
     */
    public function createTicket(Request $request,EntityManagerInterface $manager)
    {
        $ticket=new Ticket();
        $d = new DateTime('today');
        $form=$this->createForm(TicketType::class,$ticket);
        $form->handleRequest($request);
        $ticket->setDemandeur($this->getUser());
        $ticket->setTicketstatut("Nouveau");
        $ticket->setTicketcreatedate($d);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($ticket);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le ticket <strong>{$ticket->getTicketname()}</strong> a bien été enregistré!"
            );
            return $this->redirectToRoute('ticket_show',[
                'id'=>$ticket->getId()
            ]);
        }
        return $this->render('ticket/newticket.html.twig',[
            'form'=>$form->createView()
        ]);       
    }
    /**
     * @Route("/ticket/edit/{id}", name="ticket_edit")
     * @Security("is_granted('ROLE_USER') and user === t.getDemandeur()",message="Ce ticket ne vous appartient pas")
     */
    public function editticket(Ticket $t,Request $request)
    {
        $form=$this->createForm(TicketType::class,$t);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($t);
            $manager->flush();
            return $this->redirectToRoute("index");
        }
        return $this->render("ticket/editticket.html.twig",[
            'form'=>$form->createView(),
            'ticket'=>$t
        ]);
    }
    /**
     * @Route("/ticket/show/{id}", name="ticket_show")
     * @Security("is_granted('ROLE_USER') and user === t.getDemandeur()",message="Ce ticket ne vous appartient pas")
     */
    public function showTicket(Ticket $t)
    {
        return $this->render('ticket/showticket.html.twig',[
            'ticket'=>$t
        ]);
    }
    /**
     * @Route("/ticket/mytickets", name="index")
     */
    public function myTickets(UserInterface $u)
    {
        return $this->render('ticket/myticket.html.twig', ['ticket'=>$u->getTickets()]);
    }
    /**
     * @Route("/ticket/delete/{id}", name="ticket_delete")
     */
    public function removeticket(Ticket $t)
    {
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($t);
        $manager->flush();
        return $this->redirectToRoute("index");
    }
    /**
     * @Route("/ticket/alltickets", name="ticket_all")
     */
    public function alltickets()
    {
        $repo=$this->getDoctrine()->getRepository(Ticket::class);
        $t=$repo->findAll();
        return $this->render('ticket/alltickets.html.twig', [
            'ticket' => $t,
        ]);
    }
    /**
     * @Route("/ticket/assigner/{id}", name="ticket_assigner")
     * @IsGranted("ROLE_USER")
     */
    public function assigner(Ticket $t ,Request $request)
    {
        $role = $this->getDoctrine()->getManager()
        ->getRepository('App\Entity\Role')->RoleParTitre('TECHNICIEN');
        $form=$this->createForm(AssignationType::class,$t);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        $u = $this->getDoctrine()->getManager()->getRepository('App\Entity\User');
        if($form->isSubmitted() && $form->isValid()){
            $t->setTechnicien( $u->findOneById($request->request->get('TECHNICIEN')) );
            $t->setTicketstatut('Assigné');
            $manager->persist($t);
            $manager->flush();
            return $this->redirectToRoute("ticket_all");
        }
        return $this->render("ticket/assigner.html.twig",[
            'form'=>$form->createView(),
            'ticket'=>$t,
            'tech'=>$role->getUsers()
        ]);
    }
    /**
     * @Route("/ticket/valider/{id}", name="ticket_valider")
     * @IsGranted("ROLE_USER")
     */
    public function validerticket(Ticket $t,EntityManagerInterface $m)
    {
        $t->setTicketstatut("Validé");
        $m->persist($t);
        $m->flush();
        return $this->redirectToRoute("ticket_non_valider");
    }
    /**
     * @Route("/ticket/non_valider", name="ticket_non_valider")
     * @IsGranted("ROLE_USER")
     */
    public function avalider()
    {
        $repo=$this->getDoctrine()->getRepository(Ticket::class);
        $t=$repo->ticketnon();
        return $this->render('ticket/valider.html.twig', [
            'ticket' => $t,
        ]);
    }
    /**
     * @Route("/login_register", name="login_register")
     * @IsGranted("ROLE_USER")
     */
    public function acceuil(UserInterface $u)
    {
        return $this->render('ticket/home.html.twig',['r' => $u->getUserroles(),
        'u' => $u->getfirstname()]);
    }
    /**
     * @Route("/ticket_a_resoudre", name="resoudre")
     * @IsGranted("ROLE_USER")
     */
    public function ticket_technicien(UserInterface $u)
    {
        return $this->render('ticket/resoudre.html.twig', ['ticket'=>$u->getTicketsaresoudre()]);
    }
    /**
     * @Route("/ticket_resolu/{id}", name="resolu")
     * @IsGranted("ROLE_USER")
     */
    public function resolu(Ticket $t,EntityManagerInterface $m)
    {
        $d = new DateTime('today');
        $t->setTicketclosedate($d);
        $t->setTicketstatut("Résolu");
        $m->persist($t);
        $m->flush();
        return $this->redirectToRoute("resoudre");
    }
    /**
     * @Route("/a_propos", name="a_propos")
     * @IsGranted("ROLE_USER")
     */
    public function a_propos()
    {
        return $this->render('ticket/a_propos.html.twig');
    }
}
