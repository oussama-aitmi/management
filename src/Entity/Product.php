<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Groups({"public", "allowPosted"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(
     *      message = "Désignation est invalide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @Groups({"public"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(
     *      message = "Reference ne doit pas être vide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $reference;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"public", "allowPosted"})
     */
    private $smallDescription;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Choice(
     *     choices = {"DRAFT", "PUBLISHED", "DELETED"},
     *     message = "Status est invalide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $status = "PUBLISHED";

    /**
     * @ORM\Column(name="status_store", type="boolean", nullable=true)
     * @Groups({"public", "allowPosted"})
     */
    private $statusStore;

    /**
     * @ORM\Column(name="status_site_web", type="boolean", nullable=true)
     * @Groups({"public", "allowPosted"})
     */
    private $statusSiteWeb;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank(
     *      message = "Prix d'achat est invalide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $basePrice;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank(
     *      message = "Prix de vente est invalide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $sellPrice;

    /**
     * @ORM\Column(name="minimum_sales_quantity", type="digit", nullable=true)
     * @Assert\Positive(message = "quantité minimale de vente est invalide")
     * @Groups({"public", "allowPosted"})
     */
    private $minimumSalesQuantity;

    /**
     * @ORM\Column(name="maximum_sales_quantity", type="digit", nullable=true)
     * @Assert\Positive(message = "quantité maximale de vente est invalide")
     * @Groups({"public", "allowPosted"})
     */
    private $maximumSalesQuantity;

    /**
     * @ORM\Column(type="digit", nullable=true)
     * @Assert\Type(type="numeric", message="Quantité invalide.")
     * @Assert\Positive(message = "Quantité doit être supérieure de 0")
     * @Assert\NotBlank(
     *      message = "Quantité ne doit pas être vide",
     * )
     * @Groups({"public", "allowPosted"})
     */
    private $quantity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Variation", mappedBy="product", orphanRemoval=true)
     * @Groups({"variations", "allowPosted"})
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

    public function setStatus($status): self
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
    public function setReference($reference): self
    {
        $this->reference = $reference;
        return $this;
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
    public function setSmallDescription($smallDescription): self
    {
        $this->smallDescription = $smallDescription;
        return $this;
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
    public function setStatusStore($statusStore): self
    {
        $this->statusStore = $statusStore;
        return $this;
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
    public function setStatusSiteWeb($statusSiteWeb): self
    {
        $this->statusSiteWeb = $statusSiteWeb;
        return $this;
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
    public function setSellPrice($sellPrice): self
    {
        $this->sellPrice = $sellPrice;
        return $this;
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
    public function setMinimumSalesQuantity($minimumSalesQuantity): self
    {
        $this->minimumSalesQuantity = $minimumSalesQuantity;
        return $this;
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
    public function setMaximumSalesQuantity($maximumSalesQuantity): self
    {
        $this->maximumSalesQuantity = $maximumSalesQuantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity( $quantity): self
    {
        $this->quantity = !empty($quantity) ? $quantity : 0;
        return $this;
    }
}
