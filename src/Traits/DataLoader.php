<?php

namespace App\Traits;

trait DataLoader
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
}