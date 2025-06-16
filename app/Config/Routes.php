<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->post('/verify', 'Auth::verify');
$routes->get('/guest', 'Auth::guest');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');
$routes->get('/tenant', 'Tenant::index');
$routes->post('/tenant/register', 'Tenant::register');
$routes->get('/tenant/guest-login', 'Tenant::guestLogin');
$routes->post('/tenant/guest-login', 'Tenant::processGuestLogin');
$routes->get('/tenant/logout', 'Tenant::logout');
$routes->get('/tenant/api/properties', 'Tenant::getProperties');
$routes->get('tenants_management', 'Tenant::management');
$routes->get('tenants_management.php', 'Tenant::management');
$routes->get('tenant_management', 'Tenant::management');
$routes->get('tenant_management.php', 'Tenant::management');
$routes->post('api/user/login', 'UserController::login');
$routes->post('api/crud/create', 'FmsCrudController::create');
$routes->post('api/crud/update', 'FmsCrudController::update');
$routes->post('api/crud/delete', 'FmsCrudController::delete');
$routes->post('api/crud/filter', 'FmsCrudController::filter');
$routes->post('api/other/call', 'FmsOtherController::call');
$routes->post('api/other/get-user-by-key', 'FmsOtherController::getUserByKey');
$routes->post('api/other/update-user-password-by-key', 'FmsOtherController::updateUserPasswordByKey');
$routes->get('/reset-password', 'ResetPassword::index');
$routes->post('/reset-password', 'ResetPassword::send');
$routes->get('role_permissions_management', 'RolePermissionsController::index');
$routes->get('tenant_portal', 'TenantPortalController::index');
$routes->get('planner_dashboard', 'PlannerDashboardController::index');
$routes->get('assignment/(:segment)', 'AssignmentController::index/$1');
$routes->get('tenant_signature/(:segment)', 'TenantSignatureController::index/$1');
$routes->get('feedback/(:segment)', 'FeedbackController::index/$1');
$routes->get('technician_feedback/(:segment)', 'TechnicianFeedbackController::index/$1');
$routes->get('supervisor_review/(:segment)', 'SupervisorReviewController::index/$1');
$routes->get('workflow/escalations', 'WorkflowController::index');
$routes->post('workflow/escalations', 'WorkflowController::runEscalations');
$routes->get('/test_all_forms', 'Home::testAllForms');

// Add missing routes for management pages
$routes->get('properties_management', 'FmsCrudController::propertiesManagement');
$routes->get('lookup_types_management', 'FmsCrudController::lookupTypesManagement');
$routes->get('lookup_types_values_management', 'FmsCrudController::lookupTypesValuesManagement');
$routes->get('users_management', 'UserController::management');
$routes->get('teams_management', 'FmsCrudController::teamsManagement');
$routes->get('technician_skills_management', 'FmsCrudController::technicianSkillsManagement');

// Add missing dashboard routes
$routes->get('admin_dashboard', 'Dashboard::adminDashboard');
$routes->get('supervisor_dashboard', 'Dashboard::supervisorDashboard');
$routes->get('technician_dashboard', 'Dashboard::technicianDashboard');

// Test routes with mock session
$routes->get('/test_login', 'Home::testLogin');
$routes->get('/check_session', 'Home::checkSession');
// CRUD Routes (universal)
$routes->post('api/crud', 'FmsCrudController::handle');

// Other Functional Routes
$routes->post('api/assign-technician', 'FmsOtherController::assignTechnician');
$routes->post('api/upload-attachment', 'FmsOtherController::uploadAttachment');
$routes->post('api/submit-feedback', 'FmsOtherController::submitFeedback');
$routes->get('api/request-log/(:num)', 'FmsOtherController::getRequestLog/$1');
$routes->get('api/sla-summary', 'FmsOtherController::getSlaComplianceTrend');
$routes->post('api/escalate-request', 'FmsOtherController::escalateRequest');