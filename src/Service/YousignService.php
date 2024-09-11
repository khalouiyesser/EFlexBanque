<?php

namespace App\Service;

/*use Symfony\Component\Mime\Part\DataPart;*/

use PhpParser\Node\Scalar\String_;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YousignService
{
    private const PATHFILE = __DIR__ .'/../../public/';
    private const path = "C:\Users\Admin\PhpstormProjects\InnovatixPiDev\public\ ";

    private HttpClientInterface $yousignClient;
    private $path = "C:\Users\Admin\PhpstormProjects\InnovatixPiDev\public\uploads_directory\ ";
    
    public function __construct(
       // private HttpClientInterface $yousignClient,
        HttpClientInterface $yousignClient
    ) {
        $this->yousignClient = $yousignClient;
    }
    public function signatureRequest(): array
    {
        $response= $this->yousignClient->request(
            'POST',
            'signature_requests' ,
            [
                'body' => <<<JSON
                    {
                    "name": "Demande de Chéque" ,
                    "delivery_mode" : "email",
                    "timezone": "Europe/Paris"
                    }
                    JSON,
                'headers'=>[
                    'Content-Type'=>'application/json',
                ],

            ]
        );
      /*  $statusCode =$response->getStatusCode();

        if($statusCode != 201){
            throw new \Exception('Error whole creating signature request');

        }*/

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function addDocumentToSignatureRequest(string $signatureRequestId , string $filename): array
    {
        

        $formFields = [
            'nature'=>'signable_document',
            'file' => DataPart::fromPath($this->path .$filename )
//    self::PATHFILE .

        ];
        
        $formData=new FormDataPart($formFields);
        
        $headers= $formData->getPreparedHeaders()->toArray();

        $response =$this ->yousignClient->request(
            'POST',
            sprintf('signature_requests/%s/documents' , $signatureRequestId),
            [
                'headers' =>$headers,
                'body'=>$formData->bodyToIterable(),
            ]
        );
//        dd($response->toArray());
        /*$statusCode = $response ->getStatusCode();
        if($statusCode != 201){
            throw new \Exception('Error while uploading document');
        }*/
        return $response->toArray();

    }
    public function addSigner(
        string $signatureID,
        string $documentID,
        string $email,
        string $nom
    ) : array{
        $signatureRequestId = 'c3fd4243-de20-48b3-849a-ca1012ed2b59';
        $documentId = '34972edf-d033-40c0-9305-3341c3ce3744';
        $response = $this ->yousignClient->request(
            'POST',
            sprintf('signature_request/%s/signer' , $signatureRequestId),
            [
                'body'=> <<<JSON
                      {
                      "info": {
                      "first_name": "$nom",
                      "email": "$email",
                      "locale": "fr"
                      },
                      "fields" : [
                      {
                      "type": "signature",
                      "document_id": "$documentId",
                      "page" : 1,
                      "x": 77,
                      "y": 581
                      }
                      ],
                      "signature_level" : "electronic_signature",
                      "signature_authentification_mode": "no_otp"
                      }
                     JSON,
                'headers'=> [
                    'content-Type'=>'application/json' ,

                ],

            ]);
       /* $statusCode =$response -> getStatusCode();

        if($statusCode != 201){
            throw new \Exception('Error while adding signer');
        }*/
        return $response->toArray();

    }

    public function activateSignatureRequest(string $signatureRequestsId) : array
    {
        $response = $this->yousignClient->request(
            'POST',
            sprintf('signature_requests/%s/activate', $signatureRequestsId)

        );
        /*$statusCode = $response->getStatusCode();
        if($statusCode != 201){
        throw new \Exception('Error while activating signature request') ;

    }*/
        return $response -> toArray();


    }
}