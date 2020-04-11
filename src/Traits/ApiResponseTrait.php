<?php

namespace App\Traits;

use App\Exception\ApiResponseException;
use Exception;
use Symfony\Component\HttpFoundation\Response;


trait ApiResponseTrait
{

    /**
     * @param array $result
     * @param int   $code
     * @return array
     */
    protected function renderSuccess(array $result, $code = Response::HTTP_OK): array
    {
        return [
            'code' => $code,
            'type' => Response::$statusTexts[$code],
            'data'=> $result
        ];
    }

    /**
     * @param $result
     * @return array
     */
    protected function renderSuccessPostCreated($result)
    {
        return [
            'code' => Response::HTTP_CREATED,
            'type' => Response::$statusTexts[Response::HTTP_CREATED],
            'data'=> $result
        ];
    }

    /**
     * @param $result
     * @return array
     */
    protected function renderSuccessPostDeleted($result)
    {
        return [
            'code' => Response::HTTP_NO_CONTENT,
            'type' => Response::$statusTexts[Response::HTTP_NO_CONTENT],
            'data'=> $result
        ];
    }

    /**
     * @param string|null $errors
     * @throws ApiResponseException
     */
    protected function renderNotFoundResponse(?string $errors)
    {
        throw new ApiResponseException($errors, Response::HTTP_NOT_FOUND);

        //if (null !== $statusType) {
        //    $response->setStatusType($statusType);
        //}
    }

    /**
     * @param string|null $errors
     * @throws Exception
     */
    protected function renderBadRequestResponse(?string $errors)
    {
        throw new ApiResponseException($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array $submittedData
     * @throws Exception
     */
    protected function failureResponseWithSubmittedData(array $submittedData)
    {
        throw new ApiResponseException($submittedData, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array $submittedData
     * @throws Exception
     */
    protected function failurePermissionAccess(array $submittedData)
    {
        throw new ApiResponseException($submittedData, Response:: HTTP_BAD_REQUEST);
    }

}
