<?php
// src/Controller/OrderController.php
namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//#[Route('/orders', name: 'order_')]
class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //#[Route('/', name: 'list', methods:['GET'])]
    public function list(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        return $this->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    //#[Route('/{id}', name: 'show', methods:['GET'])]
    public function show($id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json([
                'success' => false,
                'message' => 'Order not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->json([
            'success' => true,
            'data' => $order
        ]);
    }

    //#[Route('/', name: 'create', methods:['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['customerId']) || empty($data['totalAmount'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data'
            ], Response::HTTP_BAD_REQUEST);
        }

        $order = new Order();
        $order->setOrderDate(new \DateTime());
        $order->setCustomerId($data['customerId']);
        $order->setTotalAmount($data['totalAmount']);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], Response::HTTP_CREATED);
    }

    //#[Route('/{id}', name: 'update', methods:['PUT'])]
    public function update(Request $request, $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json([
                'success' => false,
                'message' => 'Order not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['customerId']) || empty($data['totalAmount'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data'
            ], Response::HTTP_BAD_REQUEST);
        }

        $order->setCustomerId($data['customerId']);
        $order->setTotalAmount($data['totalAmount']);

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    //#[Route('/{id}', name: 'delete', methods:['DELETE'])]
    public function delete($id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json([
                'success' => false,
                'message' => 'Order not found'
            ], Response::HTTP_NOT_FOUND);
        }
    
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    
        return $this->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ], Response::HTTP_OK);
    }
    
}
