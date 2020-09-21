<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantTable;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RestaurantTableController extends AbstractController
{
    /**
     * @Route("/restaurant/{restaurant_id}/table", name="restaurant_table")
     * @param int $restaurant_id
     * @return Response
     */
    public function index(int $restaurant_id)
    {
        $restaurant_table = $this->getDoctrine()->getRepository(RestaurantTable::class)->
        findTablesByRestaurant($restaurant_id);

        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($restaurant_id);

        return $this->render('restaurant_table/index.html.twig', [
            'controller_name' => 'RestaurantTableController',
            'restaurant_table' => $restaurant_table,
            'restaurant_id' => $restaurant_id,
            'restaurant' => $restaurant
        ]);
    }

    /**
     * @Route("/restaurant/{restaurant_id}/table/create", name="create_restaurant_table")
     * @param int $restaurant_id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createRestaurantTable(int $restaurant_id, Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('POST')) {
            $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($restaurant_id);
            $enabled_tables = $this->getDoctrine()->getRepository(RestaurantTable::class)->findEnabledTablesByRestaurant($restaurant_id);

            if (sizeof($enabled_tables) >= $restaurant->getMaxTableCount() && $request->request->get('status_active')) {
                $error = 'This restaurant has reached maximum enabled table count.';
                return $this->render('restaurant_table/create.html.twig', [
                    'restaurant_id' => $restaurant_id,
                    'restaurant' => $restaurant,
                    'error' => $error
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $restaurant_table = new RestaurantTable();
            $restaurant_table->setRestaurantId($restaurant_id);
            $restaurant_table->setCapacity($request->request->get('capacity'));
            $restaurant_table->setNumber($request->request->get('number'));
            $restaurant_table->setStatusActive($request->request->get('status_active') ? 1 : 0);

            $errors = $validator->validate($restaurant_table);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }

            $entityManager->persist($restaurant_table);

            $entityManager->flush();

            return $this->redirectToRoute('restaurant_table', [
                'restaurant_table' => $restaurant_table,
                'restaurant_id' => $restaurant_id,
            ]);
        }
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($restaurant_id);

        return $this->render('restaurant_table/create.html.twig', ['restaurant_id' => $restaurant_id, 'restaurant' => $restaurant]);
    }

    /**
     * @Route("/restaurant/{restaurant_id}/table/edit/{id}", name="edit_restaurant_table")
     * @param int $restaurant_id
     * @param int $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function editRestaurantTable(int $restaurant_id, int $id, Request $request, ValidatorInterface $validator): Response
    {
        $restaurant_table = $this->getDoctrine()->getRepository(RestaurantTable::class)->find($id);
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($restaurant_id);

        if (!$restaurant_table) {
            throw $this->createNotFoundException(
                'No restaurant table found for id ' . $id
            );
        }

        if ($request->isMethod('POST')) {
            $enabled_tables = $this->getDoctrine()->getRepository(RestaurantTable::class)->findEnabledTablesByRestaurant($restaurant_id);

            $is_table_already_enabled = false;

            for ($i = 0; $i < sizeof($enabled_tables); $i++) {
                if ($id == $enabled_tables[$i]->getId()) {
                    $is_table_already_enabled = true;
                    break;
                }
            }

            if (sizeof($enabled_tables) >= $restaurant->getMaxTableCount() && $is_table_already_enabled == false && $request->request->get('status_active')) {
                $error = 'This restaurant has reached maximum enabled table count.';
                return $this->render('restaurant_table/edit.html.twig', [
                    'restaurant_table' => $restaurant_table,
                    'restaurant_id' => $restaurant_id,
                    'restaurant' => $restaurant,
                    'error' => $error
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $restaurant_table->setCapacity($request->request->get('capacity'));
            $restaurant_table->setNumber($request->request->get('number'));
            $restaurant_table->setStatusActive($request->request->get('status_active') ? 1 : 0);

            $errors = $validator->validate($restaurant_table);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }

            $entityManager->persist($restaurant_table);

            $entityManager->flush();

            return $this->redirectToRoute('restaurant_table', [
                'restaurant_table' => $restaurant_table,
                'restaurant_id' => $restaurant_id,
            ]);
        }

        return $this->render('restaurant_table/edit.html.twig', [
            'restaurant_table' => $restaurant_table,
            'restaurant_id' => $restaurant_id,
            'restaurant' => $restaurant
        ]);
    }

    /**
     * @Route("/restaurant/{restaurant_id}/table/view/{id}", name="view_restaurant_table")
     * @param int $restaurant_id
     * @param int $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function viewRestaurantTable(int $restaurant_id, int $id, Request $request, ValidatorInterface $validator): Response
    {
        $restaurant_table = $this->getDoctrine()->getRepository(RestaurantTable::class)->find($id);
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($restaurant_id);

        if (!$restaurant_table) {
            throw $this->createNotFoundException(
                'No restaurant table found for id ' . $id
            );
        }

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $restaurant_table->setCapacity($request->request->get('capacity'));
            $restaurant_table->setNumber($request->request->get('number'));
            $restaurant_table->setStatusActive($request->request->get('status_active') ? 1 : 0);

            $errors = $validator->validate($restaurant_table);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }

            $entityManager->persist($restaurant_table);

            $entityManager->flush();

            return $this->redirectToRoute('restaurant_table', [
                'restaurant_table' => $restaurant_table,
                'restaurant_id' => $restaurant_id,
            ]);
        }
        return $this->render('restaurant_table/view.html.twig', [
            'restaurant_table' => $restaurant_table,
            'restaurant_id' => $restaurant_id,
            'restaurant' => $restaurant
        ]);
    }

    /**
     * @Route("/restaurant/{restaurant_id}/table/delete/{id}", name="delete_restaurant_table")
     * @param int $restaurant_id
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function deleteRestaurantTable(int $restaurant_id, int $id, Request $request): Response
    {
        $restaurant_table = $this->getDoctrine()->getRepository(RestaurantTable::class)->find($id);

        if (!$restaurant_table) {
            throw $this->createNotFoundException(
                'No restaurant table found for id ' . $id
            );
        }

        if ($request->isMethod('GET')) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($restaurant_table);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant_table', [
                'restaurant_table' => $restaurant_table,
                'restaurant_id' => $restaurant_id
            ]);
        }
    }
}

