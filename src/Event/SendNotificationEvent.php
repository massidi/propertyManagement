<?php


namespace App\Event;
use App\Entity\Booking;
use App\Entity\Facturation;
use Symfony\Contracts\EventDispatcher\Event;


class SendNotificationEvent extends Event
{
    public const NAME = 'facturation.completed';


    /**
     * @var Facturation
     */
    private $facturation;

    /**
     * SendNotification constructor.
     * @param Facturation $facturation
     */
    public function __construct(Facturation  $facturation)
    {

        $this->facturation = $facturation;
    }

    public function getBooking(): Facturation
    {
        return $this->facturation;
    }

}