<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Regex(
     *     pattern="/[a-zA-Z0-9]/",
     *     match=false,
     *     message="Désignation est invalide")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(
     *      message = "Reference ne doit pas être vide",
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $smallDescription;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Choice(
     *     choices = {"DRAFT", "PUBLISHED", "DELETED"},
     *     message = "Status est invalide",
     * )
     */
    private $status;

    /**
     * @ORM\Column(name="status_store", type="boolean", nullable=true)
     */
    private $statusStore;

    /**
     * @ORM\Column(name="status_site_web", type="boolean", nullable=true)
     */
    private $statusSiteWeb;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank(
     *      message = "Prix d'achat est invalide",
     * )
     */
    private $basePrice;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank(
     *      message = "Prix de vente est invalide",
     * )
     */
    private $sellPrice;

    /**
     * @ORM\Column(name="minimum_sales_quantity", type="integer", nullable=true)
     * @Assert\Positive(message = "quantité minimale de vente est invalide",)
     */
    private $minimumSalesQuantity;

    /**
     * @ORM\Column(name="maximum_sales_quantity", type="integer", nullable=true)
     * @Assert\Positive(message = "quantité maximale de vente est invalide",)
     */
    private $maximumSalesQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive(message = "quantité maximale de vente est invalide")
     */
    private $quantity;




    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Variation", mappedBy="product", orphanRemoval=true)
     */
    private $variations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    public function __construct()
    {
        $this->variations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBasePrice(): ?string
    {
        return $this->basePrice;
    }

    public function setBasePrice(string $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * @return Collection|Variation[]
     */
    public function getVariations(): Collection
    {
        return $this->variations;
    }

    public function addVariation(Variation $variation): self
    {
        if (!$this->variations->contains($variation)) {
            $this->variations[] = $variation;
            $variation->setProduct($this);
        }

        return $this;
    }

    public function removeVariation(Variation $variation): self
    {
        if ($this->variations->contains($variation)) {
            $this->variations->removeElement($variation);
            // set the owning side to null (unless already changed)
            if ($variation->getProduct() === $this) {
                $variation->setProduct(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getSmallDescription()
    {
        return $this->smallDescription;
    }

    /**
     * @param mixed $smallDescription
     */
    public function setSmallDescription($smallDescription): void
    {
        $this->smallDescription = $smallDescription;
    }

    /**
     * @return mixed
     */
    public function getStatusStore()
    {
        return $this->statusStore;
    }

    /**
     * @param mixed $statusStore
     */
    public function setStatusStore($statusStore): void
    {
        $this->statusStore = $statusStore;
    }

    /**
     * @return mixed
     */
    public function getStatusSiteWeb()
    {
        return $this->statusSiteWeb;
    }

    /**
     * @param mixed $statusSiteWeb
     */
    public function setStatusSiteWeb($statusSiteWeb): void
    {
        $this->statusSiteWeb = $statusSiteWeb;
    }

    /**
     * @return mixed
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * @param mixed $sellPrice
     */
    public function setSellPrice($sellPrice): void
    {
        $this->sellPrice = $sellPrice;
    }

    /**
     * @return mixed
     */
    public function getMinimumSalesQuantity()
    {
        return $this->minimumSalesQuantity;
    }

    /**
     * @param mixed $minimumSalesQuantity
     */
    public function setMinimumSalesQuantity($minimumSalesQuantity): void
    {
        $this->minimumSalesQuantity = $minimumSalesQuantity;
    }

    /**
     * @return mixed
     */
    public function getMaximumSalesQuantity()
    {
        return $this->maximumSalesQuantity;
    }

    /**
     * @param mixed $maximumSalesQuantity
     */
    public function setMaximumSalesQuantity($maximumSalesQuantity): void
    {
        $this->maximumSalesQuantity = $maximumSalesQuantity;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}
