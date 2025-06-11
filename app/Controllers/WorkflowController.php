<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\WorkflowAutomation;

class WorkflowController extends Controller
{
    public function runEscalations()
    {
        $workflow = new WorkflowAutomation();
        $workflow->processEscalations();
        return $this->response->setJSON(['status' => 'ok', 'message' => 'Escalation process completed.']);
    }
}
