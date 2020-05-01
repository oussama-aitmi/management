<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\CategoryValidator;
use App\Validator\Constraints\CategoryConstraint;


/**
 * Category
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @CategoryConstraint
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"public", "allowPosted"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public", "allowPosted"})
     * @Assert\NotBlank(message="le nom de catégorie est obligatoire")
     * @Assert\Length(
     * min = "3",
     * max = "100",
     * minMessage = "Nom categorie doit faire au moins 3 caractères",
     * maxMessage = "Nom categorie ne peut pas être plus long que 100 caractères")
     * @Assert\Regex(
     *     pattern="/[a-zA-Z0-9]/",
     *     match=false,
     *     message="Nom categorie invalide")
      */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @Groups({"public"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="subCategories")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * @Groups({"allowPosted"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $subCategories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category", orphanRemoval=true)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="categories")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Please enter user Id")
     */
    private $user;


    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
        //$this->product = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent( $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(self $subCategory): self
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories[] = $subCategory;
            $subCategory->setParent($this);
        }

        return $this;
    }

    public function removeSubCategory(self $subCategory): self
    {
        if ($this->subCategories->contains($subCategory)) {
            $this->subCategories->removeElement($subCategory);
            // set the owning side to null (unless already changed)
            if ($subCategory->getParent() === $this) {
                $subCategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
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
    
}
