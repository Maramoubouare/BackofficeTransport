<?php

namespace App\Controller;
use App\Entity\Ticket;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TicketType;
use App\Form\AdminType;
use App\Form\CategoryType;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\ExFixtures;
use App\Entity\Admin;


class UserController extends AbstractController
{
    
    #[Route('/enregistrement', name: 'enregistrement')]
  public function enregistrement(Request $request, EntityManagerInterface $entityManager): Response
{
    $ticket = new Ticket();
    $form = $this->createForm(TicketType::class, $ticket);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($ticket);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }


    return $this->render('user/enregistrement.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/inscrire', name: 'inscrire')]
public function inscrire(Request $request, EntityManagerInterface $entityManager): Response
{
    $admin = new Admin();
    $form = $this->createForm(AdminType::class, $admin);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Encodez le mot de passe si nécessaire
        // Exemple avec Symfony Security Component
        // $encodedPassword = $passwordEncoder->encodePassword($admin, $admin->getPassword());
        // $admin->setPassword($encodedPassword);


        $entityManager->persist($admin);
        $entityManager->flush();

        // Redirigez vers une autre page ou retournez une réponse
        return $this->redirectToRoute('index');
    }
      return $this->render('user/inscription.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/payment', name: 'payment')]
    public function showpayment(EntityManagerInterface $entityManager): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class);
        $transaction= $transaction->findAll();
        return $this->render('user/transaction.html.twig', [
            'transaction' => $transaction,
        ]);
    }
    #[Route('/payment/{id}', name: 'supprimerpayment', methods: ['POST'])]
    public function deletepayment(Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($transaction);
        $entityManager->flush();

        return $this->redirectToRoute('payment');
    }
#[Route('/modifierbus', name: 'modifierbus')]
    public function showBusTickets(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Ticket::class)->findBy(['type' => 'bus']);

        return $this->render('user/modifier.html.twig', [
            'tickets' => $tickets,
        ]);
    }
    #[Route('/modifiertrain', name: 'modifiertrain')]
    public function TrainTickets(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Ticket::class)->findBy(['type' => 'train']);

        return $this->render('user/modifiertrain.html.twig', [
            'tickets' => $tickets,
        ]);
    }
    #[Route('/modifieravion', name: 'modifieravion')]
    public function AvionTickets(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Ticket::class)->findBy(['type' => 'avion']);

        return $this->render('user/modifieravion.html.twig', [
            'tickets' => $tickets,
        ]);
    }
    #[Route('/modifierticket/{id}', name: 'modifierticket')]
    public function modifyTicket(Ticket $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
       
            $form = $this->createForm(TicketType::class, $ticket);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
    
                return $this->redirectToRoute('modifierbus');
            }
    
            return $this->render('user/edit_ticket.html.twig', [
                'form' => $form->createView(),
                'ticket' => $ticket
            ]);
    }

    #[Route('/supprimerticket/{id}', name: 'supprimerticket', methods: ['POST'])]
    public function deleteTicket(Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($ticket);
        $entityManager->flush();

        return $this->redirectToRoute('modifierbus');
    }

    #[Route('/', name: 'index')]
    public function home(ManagerRegistry $doctrine ): Response
    {

   $ticket = $doctrine->getRepository(Ticket::class);
   $tickets= $ticket->findAll();
        return $this->render('user/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }
}

