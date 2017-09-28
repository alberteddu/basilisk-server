<?php

namespace BasiliskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="BasiliskBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="payload", type="json_array")
     */
    private $payload;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Room
     *
     * @ORM\ManyToOne(targetEntity="BasiliskBundle\Entity\Room")
     */
    private $room;

    /**
     * Get id
     *
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set payload
     *
     * @param array $payload
     *
     * @return Message
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get payload
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Message
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     *
     * @return Message
     */
    public function setRoom(Room $room): Message
    {
        $this->room = $room;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'payload' => $this->payload,
            'type' => $this->type,
        ];
    }
}

