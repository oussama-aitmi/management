<?php
namespace App\Handler;


class ExceptionWrapperHandler  {

    public function wrap($data)
    {
        /** @var \Symfony\Component\Debug\Exception\FlattenException $exception */
        $exception = $data['exception'];

        $newException = array(
            'success' => false,
            'exception' => array(
                'exceptionClass' => $exception->getClass(),
                'message' => $data['status_text']
            )
        );

        return $newException;
    }
}