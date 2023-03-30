<?php
namespace App\Controllers;

use App\Repositories\ExamRepository;
use App\Services\ExportExamService;
use ApplicationLoader;

class HomeController extends AppController
{
    protected ExportExamService $service;
    protected ExamRepository $repository;

    public function initialize()
    {
        parent::initialize();
        $this->service = ApplicationLoader::service(ExportExamService::class);
        $this->repository = ApplicationLoader::repository(ExamRepository::class);
    }

    public function index()
    {
        $countExam = $this->repository->countExam();
        dd($countExam);
        $this->render('index');
    }

    public function display()
    {
        $this->render('display');
    }
}
?>