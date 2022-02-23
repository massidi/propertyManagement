<?php


namespace App\Event;


use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

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

            SendNotificationEvent::NAME => 'OnSendSms',
        ];
    }

    public function OnSendSms(SendNotificationEvent  $sendNotification)
    {

        $ClientName = $sendNotification->getBooking()->getBooking()->getClients()[0]->getNom();

        $arrivalDate=$sendNotification->getBooking()->getBooking()->getCheckInAt()->format('d.m.Y');
        $departurDate=$sendNotification->getBooking()->getBooking()->getCheckOutAt()->format('d.m.Y');
        $clientNumber=$sendNotification->getBooking()->getBooking()->getClients()[0]->getTelephone();

//        dd([$ClientName, $arrivalDate,$departurDate]);



        $twilio = new Client(self::ACCOUNT_ID, self::AUTH_TOKEN);

        try {
            $twilio->messages
                ->create("+917433034016", // to
                    [
                        "from" => self::TWILIO_NUMBER,
                        "body" => "Bonjour, Ref cleint: $ClientName Nous vous confirmons votre réservation du mardi au nom de Monsieur $ClientName Detail de votre séjour Date d'arriver: $arrivalDate Départ :$departurDate MERCI"
                    ]
                );
        } catch (TwilioException $e) {
        }
//        dd($twilio);

    }
}