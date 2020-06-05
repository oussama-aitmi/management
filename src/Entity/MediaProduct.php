<?php

namespace App\Entity;

use App\Traits\DataLoader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MediaProduct extends Document
{
    use DataLoader;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="mediaProducts", cascade={"persist"})
     */
    private $product;

    /**
     *TODO multi files
     */
    private $uploadedFiles;


    public function __construct() {
        $this->uploadedFiles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
