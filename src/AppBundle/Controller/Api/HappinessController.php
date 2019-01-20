<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\HappinessLog;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HappinessController extends AbstractFOSRestController
{
    const RESULT_LIMIT = 5;
    const URL_SOURCE = 'https://s3-eu-west-1.amazonaws.com/novemberfive-serverside/data.json';

    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/happiness-logs")
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getLogsAction(Request $request)
    {
        $logs = $this->getDoctrine()->getRepository(HappinessLog::class)->findAll();

        $paginated = $this->paginator->paginate($logs, $request->get('page', 1), self::RESULT_LIMIT);
        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize(['data' => $paginated->getItems()], 'json');

        return new Response($data);
    }

    /**
     * @Route("/happiness-logs")
     * @param Request $request
     * @return View
     */
    public function postLogAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $log = new HappinessLog();
        $log->setHappy($request->get('happy'));
        $log->setCreatedAt(new \DateTime());

        $entityManager->persist($log);
        $entityManager->flush();

        return View::create($log, Response::HTTP_CREATED);
    }

    /**
     * @Route("/happiness-logs/export")
     * @return BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function exportLogsToCsvAction()
    {
        $logs = $this->getDoctrine()->getRepository(HappinessLog::class)->findAll();

        $data = array();
        foreach ($logs as $log) {
            $data [] = array(
                'id' => $log->getId(),
                'state' => $log->getHappy() ? 'tak' : 'nie',
                'date' => $log->getCreatedAt()->format('Y-m-d H:i:s')
            );
        }

        $serializer = $this->container->get('serializer');
        $csvData = $serializer->encode($data, 'csv');
        file_put_contents('export.csv', $csvData);

        $response = new BinaryFileResponse('export.csv');
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }

    /**
     * @Route("/happiness-logs/import")
     * @return View
     */
    public function importLogsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $logs = $this->getJsonDataFromUrl();

        foreach ($logs as $log) {
            $logObject = new HappinessLog();
            $logObject->setExternalId($log['id']);
            $logObject->setHappy($log['happy']);
            $logObject->setCreatedAt(new \DateTime($log['created_at']));

            $entityManager->persist($logObject);
            $entityManager->flush();
        }

        return View::create(['status' => 'OK'], Response::HTTP_OK);
    }

    public function getJsonDataFromUrl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, self::URL_SOURCE);
        $result = curl_exec($ch);
        curl_close($ch);
        $decodedResult = json_decode($result, true);

        return $decodedResult['data'];
    }
}
