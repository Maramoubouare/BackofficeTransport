<?php
// src/Controller/TicketController.php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ATicketController extends AbstractController
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    #[Route('/api/tickets', name: 'get_tickets', methods: ['GET'])]
    public function getTickets(): JsonResponse
    {
        $tickets = $this->ticketRepository->findAll();
        $data = [];

        foreach ($tickets as $ticket) {
            $data[] = [
                'id' => $ticket->getId(),
                'type' => $ticket->getType(),
                'companyName' => $ticket->getCompanyName(),
                'departureTime' => $ticket->getDepartureTime()->format('H:i'),
                'arrivalTime' => $ticket->getArrivalTime()->format('H:i'),
                'departureCity' => $ticket->getDepartureCity(),
                'arrivalCity' => $ticket->getArrivalCity(),
                'price' => $ticket->getPrice(),
                'numberOfPeople' => $ticket->getNombre(),
                'date' => $ticket->getDate()->format('Y-m-d'),
                'travelTime' => $ticket->getTravelTime(),
            ];
        }

        return new JsonResponse($data);
    }
}

