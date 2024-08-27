<?php

namespace App\Controller;
// src/Controller/PaymentController.php


use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/payment/create", name="payment_create", methods={"POST"})
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];
        $currency = $data['currency'];

        Stripe::setApiKey('sk_test_51PXagkAPEIphJUDQkubjyRLWjMCkAXBdNs7TURyXjeYqV1xqrKsXb7pT20SFLDx6A1IipdsZU8rVnsi0Fbzvgt5800VloLdwIW'); // Remplacez par votre clé Stripe

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_types' => ['card'],
            ]);

            // Sauvegarder la transaction dans la base de données
            $transaction = new Transaction();
            $transaction->setStripePaymentIntentId($paymentIntent->id);
            $transaction->setAmount($amount);
            $transaction->setCurrency($currency);
    
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/payment/status/{id}", name="payment_status", methods={"GET"})
     */
    public function getPaymentStatus(string $id): JsonResponse
    {
        Stripe::setApiKey('sk_test_51PXagkAPEIphJUDQkubjyRLWjMCkAXBdNs7TURyXjeYqV1xqrKsXb7pT20SFLDx6A1IipdsZU8rVnsi0Fbzvgt5800VloLdwIW'); // Remplacez par votre clé Stripe

        try {
            $paymentIntent = PaymentIntent::retrieve($id);

            return new JsonResponse(['status' => $paymentIntent->status]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/payment/webhook", name="payment_webhook", methods={"POST"})
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        Stripe::setApiKey('sk_test_51PXagkAPEIphJUDQkubjyRLWjMCkAXBdNs7TURyXjeYqV1xqrKsXb7pT20SFLDx6A1IipdsZU8rVnsi0Fbzvgt5800VloLdwIW'); // Remplacez par votre clé Stripe
        $endpointSecret = 'whsec_...'; // Remplacez par votre secret de webhook

        $payload = @file_get_contents('php://input');
        $sigHeader = $request->headers->get('stripe-signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new JsonResponse(['error' => 'Invalid payload'], JsonResponse::HTTP_BAD_REQUEST);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new JsonResponse(['error' => 'Invalid signature'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return new JsonResponse(['status' => 'success']);
    }
}

