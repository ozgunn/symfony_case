<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\FormType\OrderCreateType;
use App\FormType\OrderUpdateType;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\VarDumper\VarDumper;

class OrderController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager) {}

    /**
     * Müşterinin tüm siparişleri
     *@Route("/api/order", name="app_order", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $manager = $this->manager->getRepository(Order::class);

        $orders = $manager->findBy(['user'=>$user->getId()]);

        return $this->json($orders, Response::HTTP_OK, [], ['groups'=>'order']);
    }

    /**
     * Sipariş detayı
     *@Route("/api/order/{order}", name="app_order_detail", methods={"GET"})
     */
    public function orderDetail(Order $order): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $manager = $this->manager->getRepository(Order::class);

        $order = $manager->findOneBy(['user'=>$user->getId(), 'orderCode'=>$order->getOrderCode()]);

        return $this->json($order, Response::HTTP_OK, [], ['groups'=>'order']);
    }

    /**
     * Sipariş oluşturma
     *@Route("/api/order", name="app_order_create", methods={"POST"})
     */
    public function orderCreate(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $order = new Order();

        $form = $this->createForm(OrderCreateType::class, $order);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            $order->setUser($user);

            $this->manager->persist($order);
            $this->manager->flush();

            return $this->json($order,200, [],["groups"=>["user","order"]]);
        }

        $response = [
            'error' => true,
            'message' => 'Invalid form.'
        ];

        return $this->json($response, Response::HTTP_OK);
    }

    /**
     * Sipariş Güncelleme
     *@Route("/api/order/{order}", name="app_order_update", methods={"PUT"})
     */
    public function orderUpdate(Request $request, Order $order): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Başkasının siparişini güncelleyemez
        if ($order->getUser() !== $user) {
            $response = [
                'error' => true,
                'message' => 'Access Denied.'
            ];

            return $this->json($response, Response::HTTP_OK);
        }

        // Sipariş kargoya verilmişse güncelleyemez
        if ($order->getShippingDate()) {
            $response = [
                'error' => true,
                'message' => 'Not allowed.'
            ];

            return $this->json($response, Response::HTTP_OK);
        }

        $form = $this->createForm(OrderUpdateType::class, $order);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            $this->manager->persist($order);
            $this->manager->flush();

            return $this->json($order,200, [],["groups"=>["order"]]);
        }

        $response = [
            'error' => true,
            'message' => 'Invalid form.'
        ];

        return $this->json($response, Response::HTTP_OK);
    }

}
