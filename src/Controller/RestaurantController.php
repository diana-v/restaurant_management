<?php

namespace App\Controller;

use App\Entity\Restaurant;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index()
    {
        $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->
        findAll();

        return $this->render('restaurant/index.html.twig', [
            'controller_name' => 'RestaurantController',
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/restaurant/create", name="create_restaurant")
     */
    public function createRestaurant(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('POST')) {
            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            $restaurant = new Restaurant();
            $restaurant->setTitle($request->request->get('title'));
            $restaurant->setPhoto($request->request->get('photo'));
            $restaurant->setMaxTableCount($request->request->get('max_table_count'));
            $restaurant->setStatusActive($request->request->get('status_active'));


            $errors = $validator->validate($restaurant);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($restaurant);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            //@TODO redirect to restaurant/{id}
            return new Response('Saved new product with id '.$restaurant->getId());
        }
        return $this->render('restaurant/create.html.twig');
    }

    /**
     * @Route("/restaurant/edit/{id}", name="edit_restaurant")
     * @param int $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function editRestaurant(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        if (!$restaurant) {
            throw $this->createNotFoundException(
                'No restaurant found for id '.$id
            );
        }

        if ($request->isMethod('POST')) {
            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            $restaurant->setTitle($request->request->get('title'));
            $restaurant->setPhoto($request->request->get('photo'));
            $restaurant->setMaxTableCount($request->request->get('max_table_count'));
            $restaurant->setStatusActive($request->request->get('status_active')?1:0);


            $errors = $validator->validate($restaurant);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($restaurant);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('restaurant', [
                'id' => $restaurant->getId()
            ]);
        }
        return $this->render('restaurant/edit.html.twig', [
            'restaurant' => $restaurant
        ]);
    }

    /**
     * @Route("/restaurant/delete/{id}", name="delete_restaurant")
     * @param int $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteRestaurant(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);

        if (!$restaurant) {
            throw $this->createNotFoundException(
                'No restaurant found for id '.$id
            );
        }

        if ($request->isMethod('GET')) {
            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->remove($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant', [
                'id' => $restaurant->getId()
            ]);
        }
    }

    /**
     * @Route("/restaurant/search", name="restaurant_search")
     */
    public function searchRestaurants(Request $request) {
            if ($request->isMethod('POST')) {
                $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)
                    ->findRestaurantByTitle($request->request->get('search'));

                return $this->render('restaurant/index.html.twig', [
                    'controller_name' => 'RestaurantController',
                    'restaurants' => $restaurants
                ]);
            }
//                $entityManager = $this->getDoctrine()->getManager();
//
//                $restaurant = new Restaurant();
//                $restaurant->setTitle();
//                $restaurant->setPhoto($request->request->get('photo'));
    }
}
