<?php

namespace Module\Userdetails\Billingaddress;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends \Module\Admin\Controller
{
    public function postMethod(System\Request $request)
    {
        try {
            $data = $request->jsonPost();
            // ___debug($data);

            $user = \Model\User::find(\Model\Session::load_user()->user_id);
            $tenant = \Model\Tenant::find($user->c_tenant);

            $billingAddress = \Model\Billingaddress::find_by_user_id_and_tenant_id($user->id, $tenant->id);

            if ($billingAddress && $billingAddress != NULL) {
                foreach ($data as $d => $v) {
                    if($d){
                        $billingAddress->$d = $v ? $v : NULL;
                    }
                }
                $billingAddress->save();
            } else {
                $billingAddress = \Model\Billingaddress::create(array(
                    'user_id'       => $user->id,
                    'tenant_id'     => $tenant->id,
                    'company'       => $data['company'] ? $data['company'] : NULL,
                    'email'         => $data['email'] ? $data['email'] : NULL,
                    'tax_id'        => $data['tax_id'] ? $data['tax_id'] : NULL,
                    'vat_no'        => $data['vat_no'] ? $data['vat_no'] : NULL,
                    'address'       => $data['address'] ? $data['address'] : NULL,
                    'contact'       => $data['contact'] ? $data['contact'] : NULL,
                    'country'       => $data['country'] ? $data['country'] : NULL,
                    'state'         => $data['state'] ? $data['state'] : NULL,
                    'zip_code'      => $data['zip_code'] ? $data['zip_code'] : NULL,
                ));
            }

            return array(
                "success" => "Billing address updated successfully",
                "error" => ""
            );
        } catch (\Throwable $th) {
            ___debug($th);
            return array(
                "success" => "",
                "error" => $th->getMessage()
            );
        }
    }
}
