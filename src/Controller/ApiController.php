<?php

namespace App\Controller;

use App\Entity\Item;
use App\Service\ApiService;
use App\Service\RequestService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
        $apiService = new ApiService(ApiService::LIST);

        $items = [];
        $requestService = new RequestService($request);
        if ($requestService->validateRequest() === false) {
            $response = $apiService->build($items, $requestService->getErrors());
            return new JsonResponse($response);
        }

        $params = $request->query->all();
        $items = $this->getDoctrine()->getRepository(Item::class)->findByParams($params);

        $response = $apiService->build($items);

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        $apiService = new ApiService(ApiService::GET);

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item === null) {
            $response = $apiService->build($item, 'Item was not found.');
            return new JsonResponse($response);
        }

        $response = $apiService->build($item);
        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $apiService = new ApiService(ApiService::POST);
        $requestService = new RequestService($request);
        $item = null;
        if ($requestService->validateRequest() === false) {
            $response = $apiService->build($item, $requestService->getErrors());
            return new JsonResponse($response);
        }

        $content = json_decode($request->getContent(), true);
        $unrelatedItem = $this->getDoctrine()->getRepository(Item::class)->findByName($content['name']);
        if ($unrelatedItem !== null) {
            $response = $apiService->build($item, 'There is already one product with this name.');
            return new JsonResponse($response);
        }

        $item = new Item();
        $item->setName($content['name'])
                ->setAmount($content['amount']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $response = $apiService->build($item, $requestService->getErrors());

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function putAction(int $id, Request $request): JsonResponse
    {
        $apiService = new ApiService(ApiService::PUT);

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item === null) {
            $response = $apiService->build($item, 'Item was not found.');
            return new JsonResponse($response);
        }

        $requestService = new RequestService($request);
        if ($requestService->validateRequest() === false) {
            $response = $apiService->build($item, $requestService->getErrors());
            return new JsonResponse($response);
        }

        $content = json_decode($request->getContent(), true);
        if (isset($content['name']) === true) {
            $unrelatedItem = $this->getDoctrine()->getRepository(Item::class)->findByName($content['name'], $id);
            if ($unrelatedItem !== null) {
                $response = $apiService->build($item, 'There is already another product with this name.');
                return new JsonResponse($response);
            }

            $item->setName($content['name']);
        }

        if (isset($content['amount']) === true) {
            $item->setAmount($content['amount']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $response = $apiService->build($item);
        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $apiService = new ApiService(ApiService::DELETE);

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item === null) {
            $response = $apiService->build($item, 'Item was not found.');
            return new JsonResponse($response);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        $response = $apiService->build($item);

        return new JsonResponse($response);
    }
}