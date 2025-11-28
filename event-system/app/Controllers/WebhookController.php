<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\BookingModel;

class WebhookController extends BaseController
{
    public function paymongo()
    {
        $payload = $this->request->getBody();
        $signature = $this->request->getHeaderLine('Paymongo-Signature');
        
        // Verify webhook signature (implement proper verification)
        
        $data = json_decode($payload, true);
        
        if (isset($data['data'])) {
            $event = $data['data'];
            
            if ($event['type'] === 'payment_intent.succeeded') {
                $paymentIntent = $event['attributes'];
                $paymentIntentId = $event['id'];
                
                $paymentModel = new PaymentModel();
                $paymentModel->verifyPayMongoPayment($paymentIntentId);
            }
        }
        
        return $this->response->setStatusCode(200);
    }
}