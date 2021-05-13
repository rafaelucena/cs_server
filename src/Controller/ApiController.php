<?php

namespace App\Controller;

use App\Entity\Item;
use App\Service\ApiService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
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
        $params = $request->query->all();

        $items = $this->getDoctrine()->getRepository(Item::class)->findByParams($params);

        $service = new ApiService(ApiService::LIST, $request);
        $response = $service->build($items);

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        $service = new ApiService(ApiService::GET);
        $response = $service->build($item);

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $item = new Item();
        $item->setName($content['name'])
             ->setAmount($content['amount']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $service = new ApiService(ApiService::POST);
        $response = $service->build($item);

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
        $content = json_decode($request->getContent(), true);
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item !== null) {
            $item->setName($content['name'])
                 ->setAmount($content['amount']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
        }

        $service = new ApiService(ApiService::PUT);
        $response = $service->build($item);

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item !== null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        $service = new ApiService(ApiService::DELETE);
        $response = $service->build($item);

        return new JsonResponse($response);
    }
}