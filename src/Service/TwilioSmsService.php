<?php

namespace App\Service;

use App\Entity\Virement;
use Twilio\Rest\Client;

class TwilioSmsService

{
    public function sendSmsToClient(string $toNumber, string $message)
    {
        try {


            // Le reste du code pour l'envoi du SMS reste inchangé
            $accountSid = $_ENV['AC8aab9f4433d1f7c8dfec3d6b2817b0e2'];
            $authToken = $_ENV['5f222239c037f19637b25e08389a8aa0'];
            $fromNumber = $_ENV['+19492696499'];


            //Client Twilio pour la création et l'envoie du sms
            $client = new Client($accountSid, $authToken);

            $client->messages->create(
                $toNumber,
                [
                    'from' => $fromNumber,
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            // Log or print the exception message
            echo 'Error: ' . $e->getMessage();
        }
    }
}


