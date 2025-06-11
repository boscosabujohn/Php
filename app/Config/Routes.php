<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/verify', 'Auth::verify');
$routes->get('/guest', 'Auth::guest');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');
$routes->get('/tenant', 'Tenant::index');
$routes->get('tenants_management', ['uses' => 'Tenant::management']);
$routes->get('tenants_management.php', ['uses' => 'Tenant::management']);
$routes->get('tenant_management', ['uses' => 'Tenant::management']);
$routes->get('tenant_management.php', ['uses' => 'Tenant::management']);
$routes->post('api/user/login', ['uses' => 'UserController::login']);
$routes->post('api/crud/create', ['uses' => 'FmsCrudController::create']);
$routes->post('api/crud/update', ['uses' => 'FmsCrudController::update']);
$routes->post('api/crud/delete', ['uses' => 'FmsCrudController::delete']);
$routes->post('api/crud/filter', ['uses' => 'FmsCrudController::filter']);
$routes->post('api/other/call', ['uses' => 'FmsOtherController::call']);
$routes->post('api/other/get-user-by-key', ['uses' => 'FmsOtherController::getUserByKey']);
$routes->post('api/other/update-user-password-by-key', ['uses' => 'FmsOtherController::updateUserPasswordByKey']);
$routes->get('/reset-password', ['uses' => 'ResetPassword::index']);
$routes->post('/reset-password', ['uses' => 'ResetPassword::send']);
$routes->get('role_permissions_management', ['uses' => 'RolePermissionsController::index']);
$routes->get('tenant_portal', ['uses' => 'TenantPortalController::index']);
$routes->get('planner_dashboard', ['uses' => 'PlannerDashboardController::index']);
$routes->get('assignment/(:segment)', ['uses' => 'AssignmentController::index/$1']);
$routes->get('tenant_signature/(:segment)', ['uses' => 'TenantSignatureController::index/$1']);
$routes->get('feedback/(:segment)', ['uses' => 'FeedbackController::index/$1']);
$routes->get('technician_feedback/(:segment)', ['uses' => 'TechnicianFeedbackController::index/$1']);
$routes->get('supervisor_review/(:segment)', ['uses' => 'SupervisorReviewController::index/$1']);
$routes->get('workflow/escalations', ['uses' => 'WorkflowController::runEscalations']);
