<?php

namespace Module\Userdetails\Paymentmethod;

use App,
    System,
    Model;

/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends App\Module\ControllerAbstract
{
    public function postMethod(System\Request $request)
    {
        try {

            $user = \Model\Session::load_user()->user;
            $tenant = \Model\Tenant::find($user->c_tenant);


            // Stripe Initialization
            $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

            // Get POST data
            $data = $request->jsonPost();
            $cardNumber = (int) $data['cardNumber'];
            $expMonth = (int) $data['expMonth'];
            $expYear = (int) $data['expYear'];
            $cvc = (int) $data['cvc'];
            $name = $data['name'];

            // Create Card Token
            $stripeToken = $stripe->tokens->create([
                'card' => [
                    'number' => $cardNumber,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'cvc' => $cvc,
                    'name' => $name
                ],
            ]);

            $cardDetails = \Model\Paymentmethod::find_by_user_id_and_tenant_id($user->id, $tenant->id);
            $cus_id = '';

            if ($cardDetails && !empty($cardDetails)) {
                $cus_id = $cardDetails->cus_id;
            } else {
                // Create Customer
                $customer = $stripe->customers->create([
                    'name' => $name,
                    'email' => $user->email,
                ]);
                $cus_id = $customer->id;
            }

            // Add Card to Customer
            $source = $stripe->customers->createSource(
                $cus_id,
                ['source' => $stripeToken]
            );

            if ($source) {
                // Add record to db
                $paymentMethod = \Model\Paymentmethod::create(array(
                    'user_id'       => $user->id,
                    'tenant_id'     => $tenant->id,
                    'card_id'       => $source->id,
                    'cus_id'       => $cus_id,
                    'name'       => $name,
                    'card_number'       => $source->last4,
                    'exp_month'       => $source->exp_month,
                    'exp_year'       => $source->exp_year,
                    'card_type'       => $source->brand,
                ));
                return array(
                    "success" => "Payment method added successfully",
                    "error" => ""
                );
            } else {
                return array(
                    "success" => "",
                    "error" => "Sorry, something went wrong!"
                );
            }
        } catch (\Throwable $th) {
            return array(
                "success" => "",
                "error" => $th->getMessage()
            );
        }
    }

    public function deletemethodAction(System\Request $request)
    {
        // Stripe Initialization
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $methId = $request->param('item');
        $method = \Model\Paymentmethod::find($methId);

        $deletedS = $stripe->customers->deleteSource(
            $method->cus_id,
            $method->card_id,
            []
        );

        $method->delete();
        
        return array();
    }
}
