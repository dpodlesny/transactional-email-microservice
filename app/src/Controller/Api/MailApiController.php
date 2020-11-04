<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Mail\Api\Creator\MailCreatorInterface;
use App\Model\Mail\Api\Reader\MailReaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailApiController extends BaseApiController
{
    protected const KEY_PAGE = 'page';

    /**
     * @Route("/api/mails", methods={"GET"})
     *
     * @param Request $request
     * @param MailReaderInterface $mailReader
     *
     * @return Response
     */
    public function list(
        Request $request,
        MailReaderInterface $mailReader
    ): Response {
        return $this->createApiResponse(
            $mailReader->findPaginated($request->query->getInt(static::KEY_PAGE, 1)),
            Response::HTTP_OK,
        );
    }

    /**
     * @Route("/api/mails", methods={"POST"})
     *
     * @param Request $request
     * @param MailCreatorInterface $mailCreator
     *
     * @return Response
     */
    public function create(
        Request $request,
        MailCreatorInterface $mailCreator
    ): Response {
        $mail = $mailCreator->createFromType(
            $request->getContent(),
            $request->getContentType() ?? 'json'
        );

        return $this->createApiResponse(
            $mail,
            Response::HTTP_CREATED,
        );
    }
}
