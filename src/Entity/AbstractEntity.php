<?php

namespace App\Entity;


abstract class AbstractEntity
{
    /**
     * @param array $data
     *
     * @return $this
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->getKeysNeedNotLoad())) {
                $method = $this->getMethod($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function convertKeySnakeCaseToCamelCase($key)
    {
        return str_replace('_', '', ucwords($key, '_'));
    }

    /**
     * @return array
     */
    protected function getKeysNeedNotLoad(): array
    {
        return [
            'createdAt',
            'updatedAt',
            'slug'
        ];
    }

    /**
     * @param string $property
     *
     * @return string
     */
    protected function getMethod(string $property)
    {
        return sprintf('set%s' . ucfirst($property), null);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}