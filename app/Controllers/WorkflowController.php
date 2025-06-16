<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\WorkflowAutomation;

class WorkflowController extends Controller
{
    public function index()
    {
        // Workflow escalations management page
        return view('workflow_escalations');
    }

    public function runEscalations()
    {
        $workflow = new WorkflowAutomation();
        $workflow->processEscalations();
        return $this->response->setJSON(['status' => 'ok', 'message' => 'Escalation process completed.']);
    }
}
