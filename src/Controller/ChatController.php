<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends AbstractController
{
    private $messages = [];
    #[Route('/chat', name: 'app_chat')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
    #[Route('/chat/publish', name: 'chat_publish')]
    public function publish(Request $request, HubInterface $hub) : Response
    {
        $message = $request->request->get('message');
        $update = new Update(
            'chat/messages',
            json_encode(['message' => $message])
        );
        $hub->publish($update);

        return $this->json(['status' => 'ok']);
    }
    #[Route('/chat/fetch', name: 'chat_fetch')]
public function fetchChatMessages(): Response
{
    

    // Retrieve the initial set of chat messages from your data source
    //$chatMessages = $this->chatMessageRepository->findAll();

    // Convert the messages to an array and return a JSON response
    //$chatMessagesData = array_map(function ($message) {
    //    return ['text' => $message->getText()];
    //}, $chatMessages);

    return $this->json([]);
}
    

    
}
