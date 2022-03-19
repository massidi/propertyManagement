<?php


namespace App\Event;


use Symfony\Component\HttpKernel\Exception\HttpException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use UltraMsg\WhatsAppApi;

class NotificationsSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    // Your Account SID and Auth Token from twilio.com/console
    private  const ACCOUNT_ID  ="ACbbb40f272e3e3023e9f567405b6beb0d";

//// In production, these should be environment variables. E.g.:
    private const AUTH_TOKEN = "4aa2186695c5e2178a72c526367fcc2c";
    private  const TWILIO_NUMBER  = "+18596517765";


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [

            SendNotificationEvent::NAME => [
                ['OnSendSms',-10],['onWhatsapp',10]
            ]

        ];
    }

    public function OnSendSms(SendNotificationEvent  $sendNotification)
    {

        $ClientName = $sendNotification->getBooking()->getBooking()->getClients()[0]->getNom();

        $arrivalDate=$sendNotification->getBooking()->getBooking()->getCheckInAt()->format('d.m.Y');
        $departurDate=$sendNotification->getBooking()->getBooking()->getCheckOutAt()->format('d.m.Y');
        $createdAt=$sendNotification->getBooking()->getBooking()->getFacturation ()->getCreatedAd ()->format('l d F Y');

//        $clientNumber=$sendNotification->getBooking()->getBooking()->getClients()[0]->getTelephone();




        $twilio = new Client(self::ACCOUNT_ID, self::AUTH_TOKEN);

        try {
            $twilio->messages
                ->create("+917433034016", // to
                    [
                        "from" => self::TWILIO_NUMBER,
                        "body" => "Bonjour, Ref cleint: $ClientName Nous vous confirmons votre réservation du $createdAt au Nom de Monsieur $ClientName Detail de votre Reservation. Date d'Arriver: $arrivalDate Départ :$departurDate MERCI"
                    ]
                );
        } catch (TwilioException $e) {
        }


    }

    public function onWhatsapp(SendNotificationEvent  $sendNotification)
    {
        $ClientName = $sendNotification->getBooking()->getBooking()->getClients()[0]->getNom();

        $arrivalDate=$sendNotification->getBooking()->getBooking()->getCheckInAt()->format('d.m.Y');
        $createdAt=$sendNotification->getBooking()->getBooking()->getFacturation ()->getCreatedAd ()->format('l d F Y');
        $departurDate=$sendNotification->getBooking()->getBooking()->getCheckOutAt()->format('d.m.Y');
        $clientNumber=$sendNotification->getBooking()->getBooking()->getClients()[0]->getTelephone();


        $ultramsg_token="1ca6zx1t4m5yiyvo"; // Ultramsg.com token
        $instance_id="instance3984"; // Ultramsg.com instance id
        $client = new WhatsAppApi($ultramsg_token,$instance_id);

        $to=$clientNumber;
        $body="Bonjour, Ref cleint: $ClientName Nous vous confirmons votre réservation du $createdAt au Nom de Monsieur $ClientName Detail de votre Reservation. Date d'Arriver: $arrivalDate Départ :$departurDate MERCI";
        try {
            $client -> sendChatMessage ( $to , $body );
        }
       catch (HttpException $exception)
       {
           return $exception;
       }


    }
}