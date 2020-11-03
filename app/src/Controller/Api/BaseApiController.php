<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     * @param int $statusCode
     *
     * @return Response
     */
    public function createApiResponse($data, $statusCode = 200): Response
    {
        return new Response(
            $this->serialize($data),
            $statusCode,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }

    /**
     * @param mixed $data
     * @param string $format
     *
     * @return string
     */
    protected function serialize($data, $format = 'json'): string
    {
        return $this->serializer->serialize($data, $format, ['groups' => ['api']]);
    }
}
