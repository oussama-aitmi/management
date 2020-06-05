<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DMS\Bundle\FilterBundle\Rule as SfFilter;

abstract class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="guid", nullable=true)
     * @SfFilter\Service(service="App\Rules\DocumentRules", method="uid")
     * Assert\Uuid(message = "document.invalid.uid")
     */
    protected $uid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(
     *      maxSize = "6000000",
     *      maxSizeMessage = "Taille fichier est trop grand {{ size }}.{{ suffix }}",
     * )
     */
    protected $file;

    /**
     * @var string
     */
    protected $temp;

    /**
     * @var string
     */
    protected $originalFileName;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid): Document
    {
        $this->uid = $uid;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {

        $this->file = $file;
        if (!empty($file)) {
            $this->setOriginalFileName($file->getClientOriginalName());
        }
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return (null === $this->path) ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return (null === $this->path) ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir(): string
    {
        if(!is_dir(__DIR__.'/../../web/'.$this->getUploadDir())) {
            dd("upload folder not exist!");
        }

        return __DIR__.'/../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/products';
    }

    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    protected function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        //dd("PrePersist");
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        if (null === $this->getFile()) {
            return;
        }

        $rr = $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            try {
                unlink($this->getUploadRootDir() .'/'. $this->temp);
                // clear the temp image path
                $this->temp = null;
            } catch (\Exception $ex) {
                //dd('Exception Document Entity ...' .$ex);
            }
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();

        if ($file && file_exists($file)) {
            unlink($file);
        }
    }
}
