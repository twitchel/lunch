<?php

namespace Ninja\Lunch\LunchBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Ninja\Lunch\LunchBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection $items
     *
     * @ORM\OneToMany(targetEntity="Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item", mappedBy="user")
     */
    protected $items;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/gif"}
     * )
     * @Vich\UploadableField(mapping="profile_image", fileNameProperty="avatarName")
     *
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, name="avatar_name")
     *
     * @var string $avatarName
     */
    protected $avatarName;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * Get avatar name
     *
     * @return string The avatars file name
     */
    public function getAvatarName()
    {
        return $this->avatarName;
    }

    /**
     * Set avatar name
     *
     * @param  string $avatarName The avatars name
     * @return User    $this Reference to this for fluent interface
     */
    public function setAvatarName($newAvatarName)
    {
        $this->avatarName = $newAvatarName;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setImage(UploadedFile $image)
    {
        $this->image = $image;
        return $this;
    }
}
