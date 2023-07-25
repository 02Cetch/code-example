<?php

namespace App\Controller\Api;

use App\Exception\Service\BadInputServiceException;
use App\Exception\Service\NotFoundServiceException;
use App\Service\Admin\SettingService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/settings', name: 'api_settings')]
class SettingsController extends AbstractApiController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: '_update', methods: ['PUT', 'PATCH'])]
    public function update(
        SettingService $service,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            $this->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $this->respondWithErrors('Invalid JSON');
        }
        try {
            $service->update($data);
        } catch (NotFoundServiceException $e) {
            $this->setStatusCode(Response::HTTP_NOT_FOUND);
            return $this->respondWithErrors($e->getMessage());
        } catch (BadInputServiceException $e) {
            $this->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $this->respondWithErrors($e->getMessage());
        }
        return $this->respondWithSuccess('Settings successfully updated');
    }
}
