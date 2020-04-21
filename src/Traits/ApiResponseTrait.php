<?php

namespace App\Traits;

use App\Response\ApiResponseException;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Response;


trait ApiResponseTrait
{
    /**
     * @param $errors
     * @param int   $code
     * @return ApiResponse
     * @throws ApiResponseException
     */
    protected function renderFailureResponse($errors, $code = Response::HTTP_BAD_REQUEST): ApiResponse
    {
        $response = new ApiResponse($code, Response::$statusTexts[$code]);

        $response->set('response', $errors);

        throw new ApiResponseException($response);
    }

    /**
     * @ return array
     *
    protected function renderSuccessPostDeleted()
    {
        return [
            'status' => "success",
            'code' => Response::HTTP_NO_CONTENT,
            'message' => Response::$statusTexts[Response::HTTP_NO_CONTENT]
        ];
    }
    protected function renderNotFoundResponse(?string $errors = 'Not Found')
    {
        throw new ApiResponseException($errors, Response::HTTP_NOT_FOUND);

        //if (null !== $statusType) {
        //    $response->setStatusType($statusType);
        //}
    }

    protected function renderBadRequestResponse($errors = null)
    {
        throw new ApiResponseException($errors, Response::HTTP_BAD_REQUEST);
    }

    protected function failureResponseWithSubmittedData(array $submittedData)
    {
        throw new ApiResponseException($submittedData, Response::HTTP_NOT_FOUND);
    }

    protected function failurePermissionAccess(array $submittedData)
    {
        throw new ApiResponseException($submittedData, Response:: HTTP_BAD_REQUEST);
    }*/

}
