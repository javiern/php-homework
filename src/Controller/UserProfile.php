<?php

namespace Javiern\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\RouterInterface;

class UserProfile extends ContainerAwareController
{
    public function getProfile(Request $request, $id)
    {
        /** @var \Javiern\DAO\UserProfile $dao */
        $dao = $this->get('user-profile-dao');

        if (false !== $profile = $dao->getUserProfile($id)) {
            return new JsonResponse($profile);
        } else {
            throw new NotFoundHttpException('User was not found', null, 1000);
        }
    }

    public function remove(Request $request, $id)
    {
        /** @var \Javiern\DAO\UserProfile $dao */
        $dao = $this->get('user-profile-dao');

        if (false !== $profile = $dao->getUserProfile($id)) {
            $dao->removeUserProfile($id);

            return new JsonResponse(['msg' => 'Operation success']);
        } else {
            throw new NotFoundHttpException('User was not found', null, 1000);
        }
    }

    public function edit(Request $request, $id)
    {
        /** @var \Javiern\DAO\UserProfile $dao */
        $dao = $this->get('user-profile-dao');

        if (false !== $old = $dao->getUserProfile($id)) {
            $new = @json_decode($request->getContent(), true);
            if (json_last_error()) {
                throw new BadRequestHttpException('Malformed JSON input', null, 1001);
            }

            /** @var \Javiern\Services\UserProfileValidationService $validator */
            $validator = $this->get('user-profile-validator');
            $errors = $validator->validateEdit($old, $new);

            if (empty($errors)) {
                /** @var \Javiern\DAO\UserProfile $dao */
                $dao = $this->get('user-profile-dao');
                $dao->saveUserProfile($new);

                $router = $this->getRouter();

                return new JsonResponse([
                    'msg' => 'Operation success',
                    'profile_id' => $id,
                ], 200);
            } else {
                return new JsonResponse($errors, 400);
            }
        } else {
            throw new NotFoundHttpException('User was not found', null, 1000);
        }
    }

    public function addPicture(Request $request, $id)
    {
        if ($request->headers->get('Content-Type') != 'image/jpg' &&
            $request->headers->get('Content-Type') != 'image/jpeg') {
            throw new BadRequestHttpException('Image is not jpg', null, 300);
        }

        /** @var \Javiern\DAO\UserProfile $dao */
        $dao = $this->get('user-profile-dao');

        if (false === $profile = $dao->getUserProfile($id)) {
            throw new NotFoundHttpException('User was not found', null, 1000);
        }

        $tmpName = '/tmp/'.uniqid('img_').'.jpg';
        file_put_contents($tmpName, file_get_contents('php://input'));

        /** @var Client $client */
        $client = $this->get('olx-cdn-api');

        $response = $client->post('users/images', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($tmpName, 'rb'),
                ],
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $body = @json_decode($response->getBody(), true);
            if (json_last_error()) {
                throw new ServiceUnavailableHttpException(['error parsing response from server']);
            }
            $dao->savePictureUrl($id, $body['url']);
        } else {
            throw new BadRequestHttpException('Image is not valid', null, 300);
        }

        return new JsonResponse(['msg' => 'Operation success']);
    }

    //not requested methods,
    public function createProfile(Request $request)
    {
        $data = @json_decode($request->getContent(), true);
        if (json_last_error()) {
            throw new BadRequestHttpException('Malformed JSON input', null, 1001);
        }

        /** @var \Javiern\Services\UserProfileValidationService $validator */
        $validator = $this->get('user-profile-validator');
        $errors = $validator->validate($data);

        if (empty($errors)) {
            /** @var \Javiern\DAO\UserProfile $dao */
            $dao = $this->get('user-profile-dao');
            $id = $dao->newUserProfile($data);

            $router = $this->getRouter();

            return new JsonResponse([
                    'msg' => 'Operation success',
                    'profile_id' => $id,
            ], 201, [
                'Location' => $router->generate('get_profile', ['id' => $id], RouterInterface::ABSOLUTE_URL),
            ]);
        } else {
            return new JsonResponse($errors, 400);
        }
    }
}
