<?php
declare(strict_types=1);

namespace App\Controller\Api;

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
}
