<?php

namespace App\Jobs;

use CodeIgniter\Queue\BaseJob;
use CodeIgniter\Queue\Interfaces\JobInterface;
use Exception;

class Email extends BaseJob implements JobInterface
{
    public function process()
    {
        $email  = service('email', null, false);
        
        $result = $email
            ->setFrom('niranjannsahoo@gmail.com', 'Niranjan')
            ->setTo('niranjan@wassan.org')
            ->setSubject('My test email')
            ->setMessage($this->data['message'])
            ->send(false);

        if (! $result) {
            throw new Exception($email->printDebugger('headers'));
        }

        return $result;
    }
}
