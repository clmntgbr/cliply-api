<?php

declare(strict_types=1);

namespace App\Presentation\Webhook\Core;

use App\Domain\Core\Dto\TranscriptAudioSuccess;
use Exception;
use Override;
use Psr\Log\LoggerInterface;
use SensitiveParameter;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

final class TranscriptAudioSuccessRequestParser extends AbstractRequestParser
{
    public const string WEBHOOK_NAME = 'transcriptaudiosuccess';

    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Override]
    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new IsJsonRequestMatcher(),
            new MethodRequestMatcher('POST'),
        ]);
    }

    /**
     * @throws JsonException
     */
    #[Override]
    protected function doParse(Request $request, #[SensitiveParameter] string $secret): RemoteEvent
    {
        $authToken = $request->headers->get('X-Authentication-Token');
        if ($authToken !== $secret) {
            $this->logger->error('Invalid authentication token.', [
                'auth_token' => $authToken,
                'secret' => $secret,
            ]);
            throw new RejectWebhookException(Response::HTTP_UNAUTHORIZED, 'Invalid authentication token.');
        }

        if (! $request->getPayload()->has('video_id') || ! $request->getPayload()->has('clip_id')) {
            $this->logger->error('Request payload does not contain required fields.', [
                'payload' => $request->getPayload()->all(),
            ]);
            throw new RejectWebhookException(Response::HTTP_BAD_REQUEST, 'Request payload does not contain required fields.');
        }

        try {
            $payload = $request->getPayload();
            $data = $this->denormalizer->denormalize($payload->all(), TranscriptAudioSuccess::class);
        } catch (Exception) {
            $this->logger->error('Invalid payload.', [
                'payload' => $request->getPayload()->all(),
            ]);
            throw new RejectWebhookException(Response::HTTP_BAD_REQUEST, 'Invalid payload');
        }

        return new RemoteEvent(
            $payload->getString('clip_id'),
            $payload->getString('video_id'),
            ['payload' => $data],
        );
    }
}
