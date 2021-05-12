<?php

namespace App\Controller;

use App\Entity\Item;
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
        $response = [
            'success' => false,
            'data' => [
                'quantity' => 0,
                'list' => [],
            ],
        ];

        $filter = $request->query->all();

        /** @var EntityManager **/
        $em = $this->getDoctrine()->getManager();
        /** @var QueryBuilder **/
        $qb = $em->createQueryBuilder();
        $qb->select(['i'])
           ->from(Item::class, 'i');

        if (empty($filter['has_more_than']) === false) {
            $qb->andWhere('i.amount > :value')
               ->setParameter('value', $filter['has_more_than']);
        } elseif (isset($filter['has_stock']) === true) {
            if ($filter['has_stock'] === 'true') {
                $qb->andWhere('i.amount > :value')
                   ->setParameter('value', 0);
            } else {
                $qb->andWhere('i.amount = :value')
                   ->setParameter('value', 0);
            }
        }

        $items = $qb->getQuery()
                    ->getResult();

        foreach ($items as $item) {
            $response['success'] = true;
            $response['data']['quantity']++;
            $response['data']['list'][] = $item->toArray();
        }

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item !== null) {
            $response = [
                'success' => true,
                'data' => $item->toArray(),
            ];
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        $content = json_decode($request->getContent(), true);

        $item = new Item();
        $item->setName($content['name'])
             ->setAmount($content['amount']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        if ($item->getId() !== null) {
            $response = [
                'success' => true,
                'data' => $item->toArray(),
            ];
        }

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
        $response = [
            'success' => false,
            'data' => [],
        ];

        $content = json_decode($request->getContent(), true);
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item !== null) {
            $item->setName($content['name'])
                 ->setAmount($content['amount']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $response = [
            'success' => true,
            'data' => $item->toArray(),
        ];

        return new JsonResponse($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($item !== null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();

            $response = [
                'success' => true,
                'data' => [],
            ];
        }

        return new JsonResponse($response);
    }
}