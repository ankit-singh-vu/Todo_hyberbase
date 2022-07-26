<?php

namespace App;

use DocuSign\eSign\Configuration;
use DocuSign\eSign\Client\ApiClient;

/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Docusignlib
{
    private $userName = '';
    private $password = '';
    private $accId = '';
    private $basePath = '';
    private $clientId = '';
    private $clientSecret = '';

    public function __construct()
    {
        $this->userName     = getenv('DS_USER_NAME');
        $this->password     = getenv('DS_PASSWORD');
        $this->accId        = getenv('DS_ACCOUNT_ID');
        $this->basePath     = getenv('DS_BASE_PATH');
        $this->clientId     = getenv('DS_CLIENT_ID');
        $this->clientSecret = getenv('DS_SECRET_KEY');
    }

    protected function getAuthHeader()
    {
        return json_encode([
            'Username' => $this->userName,
            'Password' => $this->password,
            'IntegratorKey' => $this->clientId
        ]);
    }

    protected function authDocU()
    {
        $config = new Configuration();
        $config->setHost($this->basePath . '/restapi');
        $config->addDefaultHeader("X-DocuSign-Authentication", $this->getAuthHeader());
        $apiClient = new ApiClient($config);

        return $apiClient;
    }

    public function checkdocAction()
    {
        $user = \Model\Session::load_user()->user;
        $tenant = \Model\Tenant::find($user->c_tenant);

        $docs = \Model\Sentdoc::find_all_by_tenant_id_and_status($tenant->id, DOC_SENT);
        $incompleteDocs = 0;

        if (count($docs) > 0) {
            foreach ($docs as $doc) {
                $api_client = $this->authDocU();
                $envelope_api = new \DocuSign\eSign\Api\EnvelopesApi($api_client);
                $results = $envelope_api->getEnvelope($this->accId, $doc->envelope_id);
                $results = json_decode($results);
                if ($results->status == 'completed') {
                    $doc->status = 1;
                    $doc->save();
                } else {
                    $incompleteDocs++;
                }
            }
        }
        return $incompleteDocs;
    }
}
