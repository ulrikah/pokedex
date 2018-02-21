<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\FormValidator;
use \App\System\Settings;
use \App\System\Mailer;
use \App\Controllers\Controller;
use \App\Models\ReportsModel;
use \DateTime;

class ReportsController extends Controller {

    public function all() {
        $model = new ReportsModel();
        $data  = $model->all($_COOKIE['user']);

        $this->render('pages/reports.twig', [
            'title'       => 'Reports',
            'description' => 'Reports - Just a simple inventory management system.',
            'page'        => 'reports',
            'reports'  => $data
        ]);
    }

    public function add() {
        if(!empty($_POST)) {
            $title     = isset($_POST['title']) ? $_POST['title'] : '';
            $validator = new FormValidator();
            $validator->notEmpty('title', $title, "Your title must not be empty");

            if($validator->isValid()) {
                $model = new ReportsModel();
                $file  = $model->generate();
                $model->create([
                    'title'       => $title,
                    'file'        => $file,
                    'user'        => $_SESSION['auth'],
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                $content = App::getTwig()->render('mail_report.twig', [
                    'username'    => $_SESSION['auth'],
                    'file'        => $title,
                    'link'        => Settings::getConfig()['url'] . 'uploads/' . $file,
                    'title'       => Settings::getConfig()['name'],
                    'description' => Settings::getConfig()['description']
                ]);

                $mailer = new Mailer();
                $mailer->setFrom(Settings::getConfig()['mail']['from'], 'Mailer');
                $mailer->addAddress($_SESSION['email']);
                $mailer->Subject = 'Hello ' . $_SESSION['auth'] . ', your report is ready!';
                $mailer->msgHTML($content);
                $mailer->send();

                App::redirect('reports');
            }

            else {
                $this->render('pages/reports_add.twig', [
                    'title'       => 'Add report',
                    'description' => 'Reports - Just a simple inventory management system.',
                    'page'        => 'reports',
                    'errors'      => $validator->getErrors(),
                    'data'        => [
                        'title'       => $title,
                    ]
                ]);
            }
        }

        else {
            $this->render('pages/reports_add.twig', [
                'title'       => 'Add report',
                'description' => 'Reports - Just a simple inventory management system.',
                'page'        => 'reports'
            ]);
        }
    }

    public function delete($id) {
        if(!empty($_POST)) {
            $model = new ReportsModel();
            $file  = $model->find($id)->file;
            unlink(__DIR__ . '/../../public/uploads/' . $file);
            $model->delete($id);

            App::redirect('reports');
        }

        else {
            $model = new ReportsModel();
            $data = $model->find($id);
            $this->render('pages/reports_delete.twig', [
                'title'       => 'Delete report',
                'description' => 'Reports - Just a simple inventory management system.',
                'page'        => 'reports',
                'data'        => $data
            ]);
        }
    }

}
