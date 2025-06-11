<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\FmsCrudModel;

class WorkflowAutomation
{
    protected $crudModel;
    public function __construct()
    {
        $this->crudModel = new FmsCrudModel();
    }

    /**
     * Check for delayed tasks and escalate/notify as needed
     */
    public function processEscalations()
    {
        $today = Time::now()->toDateString();
        // Get all non-completed, overdue requests
        $delayed = $this->crudModel->filter('fms_maintenance_requests', [
            'p_status_not' => 'Completed',
            'p_due_date_lt' => $today
        ]);
        foreach ($delayed as $req) {
            // Escalate if not already escalated
            if (empty($req['escalated_at'])) {
                $this->crudModel->update('fms_maintenance_requests', [
                    'id' => $req['id'],
                    'escalated_at' => Time::now()->toDateTimeString(),
                    'status' => 'Escalated'
                ]);
                $this->notifySupervisor($req);
            }
        }
    }

    /**
     * Notify supervisor or admin about escalation
     */
    public function notifySupervisor($request)
    {
        // Find supervisor(s) for the property or global
        $supervisors = $this->crudModel->filter('fms_users', [ 'role' => 'Supervisor' ]);
        foreach ($supervisors as $sup) {
            // Save notification (could be email, SMS, or in-app)
            $this->crudModel->create('fms_notifications', [
                'user_id' => $sup['id'],
                'title' => 'Task Escalation',
                'message' => 'Request #' . $request['id'] . ' is delayed and has been escalated.',
                'created_at' => Time::now()->toDateTimeString(),
                'status' => 'unread'
            ]);
        }
    }

    /**
     * Notify technician/tenant on assignment, completion, or feedback
     */
    public function notifyUser($userId, $title, $message)
    {
        $this->crudModel->create('fms_notifications', [
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'created_at' => Time::now()->toDateTimeString(),
            'status' => 'unread'
        ]);
    }
}
