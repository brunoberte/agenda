<?php
declare(strict_types=1);

namespace Agenda\Controllers;

use Agenda\Models\Contact;

class DashboardController extends AppController
{
    public function index(): void
    {
        $this->render('Dashboard/index.html.twig', $this->getDashboardData());
    }

    private function getDashboardData()
    {
        $contacts = (new Contact())->findAll();

        $ddd_data = [];
        $mail_data = [];

        array_map(function(Contact $contact) use(&$ddd_data) {
            preg_match('/^\((\d{2})\)/', $contact->main_number, $matches);
            if (isset($matches[1])) {
                $ddd = $matches[1];
                if (!isset($ddd_data[$ddd])) {
                    $ddd_data[$ddd] = 0;
                }
                $ddd_data[$ddd]++;
            }
        }, $contacts);
        arsort($ddd_data);
        $ddd_data = array_slice($ddd_data, 0, 10, true);

        array_map(function(Contact $contact) use(&$mail_data) {
            $domain = substr($contact->email, strpos($contact->email, '@') + 1);
            if (!isset($mail_data[$domain])) {
                $mail_data[$domain] = 0;
            }
            $mail_data[$domain]++;
        }, $contacts);
        arsort($mail_data);
        $mail_data = array_slice($mail_data, 0, 10, true);

        return compact('ddd_data', 'mail_data');
    }
}


