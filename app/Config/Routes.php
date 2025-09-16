<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== PUBLIC ROUTES ==================== //

// ------------- Public Landing & Authentication Routes ------------- //
// Public landing page (always accessible). Authenticated users get redirected to their dashboard inside controller.
$routes->get('/', 'PublicController::index');

// Guest-only routes (login etc.)
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('login', 'KNectController::login');
});
$routes->post('loginProcess', 'AuthController::loginProcess');
$routes->post('logout', 'AuthController::logout');
$routes->get('change-password', 'AuthController::changePassword');
$routes->post('change-password-process', 'AuthController::changePasswordProcess');

// ---------------- Profiling Routes ------------------ //
$routes->get('profiling', 'ProfilingController::profiling');
$routes->post('profiling/step1', 'ProfilingController::profilingStep1');
$routes->post('profiling/step2', 'ProfilingController::profilingStep2');
$routes->post('profiling/step3', 'ProfilingController::profilingStep3');
$routes->post('profiling/submit', 'ProfilingController::profilingSubmit');
$routes->get('profiling/reupload/(:num)', 'ProfilingController::reuploadById/$1');
// Reset profiling session data (used by client timeout)
$routes->match(['GET','POST'], 'profiling/reset', 'ProfilingController::resetProfiling');
// Profiling navigation
$routes->post('profiling/backToStep1', 'ProfilingController::backToStep1');
$routes->post('profiling/backToStep2', 'ProfilingController::backToStep2');
$routes->post('profiling/backToStep3', 'ProfilingController::backToStep3');
$routes->post('profiling/backToStep4', 'ProfilingController::backToStep4');

// Module: Document (Public preview)
$routes->get('previewDocument/(:segment)/(:segment)', 'DocumentController::preview/$1/$2');

// ================= AUTHENTICATED USER ROUTES ================= //
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // ================= USER TYPE: KK ================= //
    // Module: KK
    $routes->get('kk/dashboard', 'KKController::dashboard');
    $routes->get('kk/profile', 'KKController::profile');
    $routes->get('kk/attendance', 'KKController::attendance');
    // KK Settings & Profile Management
    $routes->get('kk/settings', 'KKController::settings');
    $routes->post('kk/settings/profile', 'KKController::updateProfile');
    $routes->post('kk/settings/password', 'KKController::updatePassword');
    $routes->post('kk/profile/upload-photo', 'KKController::uploadProfilePhoto');
    $routes->get('kk/settings/check-email', 'KKController::checkEmail');

    // ================= USER TYPE: SK ================= //
    // Module: SK
    $routes->get('sk/dashboard', 'AnalyticsController::skDashboard');
    $routes->get('sk/profile', 'SKController::profile');
    $routes->get('sk/member', 'SKController::member');
    $routes->get('sk/settings', 'SKController::settings');
    $routes->get('sk/youth-profile', 'SKController::youthProfile');
    $routes->get('sk/rfid-assignment', 'SKController::rfidAssignment');
    $routes->post('sk/saveRFID', 'SKController::saveRFID');
    $routes->post('sk/checkRFIDDuplicate', 'SKController::checkRFIDDuplicate');
    // Module: Attendance (SK)
    $routes->get('sk/attendance', 'AttendanceController::attendance');
    $routes->post('sk/getEventAttendanceSettings', 'AttendanceController::getEventAttendanceSettings');
    $routes->post('sk/saveEventAttendanceSettings', 'AttendanceController::saveEventAttendanceSettings');
    $routes->get('sk/startAttendance/(:num)', 'AttendanceController::startAttendance/$1');
    $routes->get('sk/attendanceDisplay/(:num)', 'AttendanceController::attendanceDisplay/$1');
    $routes->post('sk/getAttendanceData', 'AttendanceController::getAttendanceData');
    // Updated: Use AttendanceController handlers for processing and status
    $routes->post('sk/processAttendance', 'AttendanceController::processAttendance');
    $routes->get('sk/getAttendanceStatus/(:num)', 'AttendanceController::getAttendanceStatus/$1');
    $routes->get('sk/getSessionStatus/(:num)', 'AttendanceController::getSessionStatus/$1');
    $routes->post('sk/autoMarkTimeouts', 'AttendanceController::autoMarkTimeouts');
    $routes->post('sk/autoTimeoutSession', 'AttendanceController::autoTimeoutSession');
    $routes->get('sk/checkAttendanceSettings', 'AttendanceController::checkAttendanceSettings');
    // Reports & Live view
    $routes->get('sk/attendanceReport/(:num)', 'AttendanceController::attendanceReport/$1');
    $routes->post('sk/attendance-report-excel/(:num)', 'AttendanceController::generateAttendanceReportExcel/$1');
    $routes->post('sk/attendance-report-word/(:num)', 'AttendanceController::generateAttendanceReportWord/$1');
    $routes->get('sk/liveAttendance/(:num)', 'SKController::liveAttendance/$1');
    // Attendance Export (list-level)
    $routes->post('sk/generate-attendance-excel', 'SKController::generateAttendanceExcel');
    $routes->post('sk/generate-attendance-pdf', 'SKController::generateAttendancePDF');
    $routes->post('sk/generate-attendance-word', 'SKController::generateAttendanceWord');
    
    // Module: KK List (SK)
    $routes->get('sk/getKKListData', 'SKController::getKKListData');
    $routes->post('sk/generate-kk-pdf', 'SKController::generateKKListPDF');
    $routes->post('sk/generate-kk-word', 'SKController::generateKKListWord');
    $routes->post('sk/generate-kk-excel', 'SKController::generateKKListExcel');
    // KK List data (for client-side PDF generation)
    $routes->get('sk/kk-list-data', 'SKController::getKKListData');

    // SK Officials & Account Settings
    $routes->get('sk/sk-official', 'SKController::skOfficial');
    $routes->get('sk/account-settings', 'SKController::accountSettings');
    $routes->post('sk/account-settings/profile', 'SKController::updateProfile');
    $routes->post('sk/account-settings/password', 'SKController::updatePassword');
    $routes->get('sk/account-settings/check-email', 'SKController::checkEmail');
    $routes->get('sk/user-management', 'SKController::userManagement');

    // ================= USER TYPE: Pederasyon ================= //
    // Module: Pederasyon
    $routes->get('pederasyon/dashboard', 'AnalyticsController::pederasyonDashboard');
    $routes->get('pederasyon/profile', 'PederasyonController::profile');
    $routes->get('pederasyon/member', 'PederasyonController::member');
    $routes->get('pederasyon/youthlist', 'PederasyonController::youthlist');
    $routes->get('pederasyon/ped-officers', 'PederasyonController::pedOfficers');
    $routes->get('pederasyon/settings', 'PederasyonController::settings');
    // Pederasyon Account Settings
    $routes->get('pederasyon/account-settings', 'PederasyonController::accountSettings');
    $routes->post('pederasyon/account-settings/profile', 'PederasyonController::updateProfile');
    $routes->post('pederasyon/account-settings/password', 'PederasyonController::updatePassword');
    $routes->get('pederasyon/account-settings/check-email', 'PederasyonController::checkEmail');
    // Pederasyon Attendance
    $routes->get('pederasyon/attendance', 'AttendanceController::pederasyonAttendance');
    $routes->post('pederasyon/getEventAttendanceSettings', 'AttendanceController::getEventAttendanceSettings');
    $routes->post('pederasyon/saveEventAttendanceSettings', 'AttendanceController::saveEventAttendanceSettings');
    $routes->get('pederasyon/startAttendance/(:num)', 'AttendanceController::pederasyonStartAttendance/$1');
    $routes->get('pederasyon/attendanceDisplay/(:num)', 'AttendanceController::pederasyonAttendanceDisplay/$1');
    $routes->post('pederasyon/getAttendanceData', 'AttendanceController::getAttendanceData');
    $routes->post('pederasyon/processAttendance', 'AttendanceController::processAttendance');
    $routes->get('pederasyon/attendanceReport/(:num)', 'AttendanceController::attendanceReport/$1');
    $routes->post('pederasyon/attendance-report-excel/(:num)', 'PederasyonController::generateAttendanceReportExcel/$1');
    $routes->post('pederasyon/attendance-report-word/(:num)', 'PederasyonController::generateAttendanceReportWord/$1');
    $routes->get('pederasyon/liveAttendance/(:num)', 'PederasyonController::liveAttendance/$1');
    $routes->post('pederasyon/autoMarkTimeouts', 'AttendanceController::autoMarkTimeouts');
    $routes->get('pederasyon/getAttendanceStatus/(:num)', 'AttendanceController::getAttendanceStatus/$1');
    $routes->get('pederasyon/getSessionStatus/(:num)', 'AttendanceController::getSessionStatus/$1');
    $routes->post('pederasyon/autoTimeoutSession', 'AttendanceController::autoTimeoutSession');
    // Module: Pederasyon AJAX
    $routes->post('updateOfficerPosition', 'PederasyonController::updateOfficerPosition');
    $routes->post('bulkUpdateOfficerPosition', 'PederasyonController::bulkUpdateOfficerPosition');
    $routes->post('pederasyon/generate-official-list-word', 'PederasyonController::generateOfficialListWord');
    $routes->post('pederasyon/generate-official-list-excel', 'PederasyonController::generateOfficialListExcel');
    $routes->get('pederasyon/credentials-data', 'PederasyonController::getCredentialsData');
    $routes->post('pederasyon/generate-credentials', 'PederasyonController::generateCredentials');
    $routes->post('pederasyon/generate-credentials-pdf', 'PederasyonController::generateCredentialsPDF');
    $routes->post('pederasyon/generate-credentials-word', 'PederasyonController::generateCredentialsWord');
    $routes->post('pederasyon/generate-credentials-excel', 'PederasyonController::generateCredentialsExcel');

    // ============== Module: Member Management ============== //
    $routes->post('getUserInfo', 'MemberController::getUserInfo');
    $routes->post('updateUserType', 'MemberController::updateUserType');
    $routes->post('bulkUpdateUserType', 'PederasyonController::bulkUpdateUserType');
    $routes->post('checkSKChairmanByBarangay', 'PederasyonController::checkSKChairmanByBarangay');
    $routes->post('updateUserPosition', 'MemberController::updateUserPosition');
    $routes->post('bulkUpdateUserPosition', 'MemberController::bulkUpdateUserPosition');

    // ============== Module: User Verification ============== //
    $routes->post('approved/(:num)', 'SKController::approved/$1');
    $routes->post('reject/(:num)', 'SKController::reject/$1');
    $routes->post('reverify/(:num)', 'SKController::reverify/$1');

    // ============== Module: User Status Management ============== //
    $routes->get('user-status/inactive', 'UserStatusController::getInactiveUsers');
    $routes->get('user-status/reactivated', 'UserStatusController::getReactivatedUsers');
    $routes->get('user-status/deactivated', 'UserStatusController::getDeactivatedUsers');
    $routes->get('user-status/verified', 'UserStatusController::getVerifiedUsers');
    $routes->get('user-status/available-zones', 'UserStatusController::getAvailableZones');
    $routes->post('user-status/check', 'UserStatusController::runStatusCheck');
    $routes->post('user-status/reactivate', 'UserStatusController::reactivateUser');
    $routes->post('user-status/deactivate', 'UserStatusController::deactivateUser');

    // ============== Module: Document (Authenticated) ============== //
    $routes->get('documents/templates', 'DocumentController::getTemplates');
    $routes->post('documents/update-template', 'DocumentController::updateTemplate');
    $routes->post('documents/generate/(:num)', 'DocumentController::generateDocument/$1');
    $routes->post('documents/generate-kk-list', 'DocumentController::generateKKList');
    $routes->post('documents/upload-logo', 'DocumentController::uploadLogo');
    $routes->get('documents/logos', 'DocumentController::getLogos');
    // Pederasyon specific logo upload endpoint
    $routes->post('pederasyon/upload-logo', 'DocumentController::uploadLogo');

    // ============================================================================
    // UNIFIED DOCUMENT ROUTES (ALL ROLES)
    // ============================================================================
    $routes->get('/documents', 'DocumentMainController::index');
    $routes->get('/documents/detail/(:num)', 'DocumentMainController::detail/$1');
    $routes->get('/documents/api/detail/(:num)', 'DocumentMainController::apiDetail/$1');
    $routes->get('/documents/download/(:num)', 'DocumentMainController::download/$1');
    $routes->get('/documents/preview/(:num)', 'DocumentMainController::preview/$1');
    $routes->get('/documents/shared', 'DocumentMainController::sharedDocuments');

    // ============== Module: Document Test ============== //
    $routes->get('test-document-upload', 'DocumentTestController::testUpload');

    // ============== Module: Event ============== //
    $routes->get('events', 'EventController::index');
    $routes->get('events/barangay', 'EventController::barangayEvents'); // SK Officials barangay-specific
    $routes->get('events/create', 'EventController::create');
    $routes->post('events/store', 'EventController::store');
    $routes->get('events/edit/(:num)', 'EventController::edit/$1');
    $routes->post('events/update/(:num)', 'EventController::update/$1');
    $routes->get('events/delete/(:num)', 'EventController::delete/$1');
    $routes->get('events/calendar', 'EventController::calendar');
    $routes->get('events/json', 'EventController::getEventsJson');
    $routes->post('events/ajax_add', 'EventController::ajax_add');
    $routes->get('city-events', 'EventController::cityEvents');
    // Removed manual publish testing route
    $routes->post('events/bulk_delete', 'EventController::bulkDelete');

    // ============== Module: Google Calendar ============== //
    $routes->get('google-calendar/connect', 'GoogleCalendarController::connect');
    $routes->get('google-calendar/callback', 'GoogleCalendarController::callback');
    $routes->post('google-calendar/add-event', 'GoogleCalendarController::addEvent');

    // ============== Module: Profile ============== //
    $routes->get('profile/picture/(:segment)', 'ProfileController::getProfilePicture/$1');

    // ============== Module: Analytics ============== //
    // Pederasyon Analytics (City-wide)
    $routes->get('pederasyon/analytics', 'AnalyticsController::pederasyonDashboard');
    $routes->get('analytics/pederasyon/gender-distribution', 'AnalyticsController::getGenderDistribution');
    $routes->get('analytics/pederasyon/age-distribution', 'AnalyticsController::getAgeGroupDistribution');
    $routes->get('analytics/pederasyon/youth-classification', 'AnalyticsController::getYouthClassificationDistribution');
    $routes->get('analytics/pederasyon/civil-status', 'AnalyticsController::getCivilStatusDistribution');
    $routes->get('analytics/pederasyon/work-status', 'AnalyticsController::getWorkStatusDistribution');
    $routes->get('analytics/pederasyon/educational-background', 'AnalyticsController::getEducationalBackgroundDistribution');
    $routes->get('analytics/pederasyon/gender-by-barangay', 'AnalyticsController::getGenderByBarangay');

    // Event Analytics
    $routes->get('pederasyon/event-analytics', 'AnalyticsController::pederasyonEventAnalytics');
    $routes->get('analytics/pederasyon/event-participation-trend', 'AnalyticsController::getEventParticipationTrend');
    $routes->get('analytics/pederasyon/top-engaged-barangays', 'AnalyticsController::getTopEngagedBarangays');
    $routes->get('analytics/pederasyon/top-active-members', 'AnalyticsController::getTopActiveMembers');
    $routes->get('analytics/pederasyon/attendance-consistency', 'AnalyticsController::getAttendanceConsistency');
    $routes->get('analytics/pederasyon/popular-event-categories', 'AnalyticsController::getMostPopularEventCategories');
    $routes->get('analytics/pederasyon/event-reach', 'AnalyticsController::getEventReach');
    $routes->get('analytics/pederasyon/participation-by-gender', 'AnalyticsController::getParticipationByGenderPerEvent');

    // Document Analytics
    $routes->get('pederasyon/document-analytics', 'AnalyticsController::pederasyonDocumentAnalytics');
    $routes->get('analytics/pederasyon/document-categories', 'AnalyticsController::getMostAccessedDocumentCategories');
    $routes->get('analytics/pederasyon/document-approval-time', 'AnalyticsController::getDocumentApprovalTime');
    $routes->get('analytics/pederasyon/top-downloaded-documents', 'AnalyticsController::getTopDownloadedDocuments');

    // Performance Analytics
    $routes->get('pederasyon/performance-analytics', 'AnalyticsController::pederasyonPerformanceAnalytics');
    $routes->get('analytics/pederasyon/barangay-performance-score', 'AnalyticsController::getBarangayPerformanceScore');
    $routes->get('analytics/pederasyon/inactive-members', 'AnalyticsController::getInactiveMembers');

    // SK Analytics (Barangay-specific)
    $routes->get('sk/analytics', 'AnalyticsController::skDashboard');
    $routes->get('analytics/sk/gender-distribution', 'AnalyticsController::getGenderDistribution');
    $routes->get('analytics/sk/age-distribution', 'AnalyticsController::getAgeGroupDistribution');
    $routes->get('analytics/sk/youth-classification', 'AnalyticsController::getYouthClassificationDistribution');
    $routes->get('analytics/sk/civil-status', 'AnalyticsController::getCivilStatusDistribution');
    $routes->get('analytics/sk/work-status', 'AnalyticsController::getWorkStatusDistribution');
    $routes->get('analytics/sk/educational-background', 'AnalyticsController::getEducationalBackgroundDistribution');

    // SK Event Analytics
    $routes->get('sk/event-analytics', 'AnalyticsController::skEventAnalytics');
    $routes->get('analytics/sk/event-participation-trend', 'AnalyticsController::getEventParticipationTrend');
    $routes->get('analytics/sk/top-active-members', 'AnalyticsController::getTopActiveMembers');
    $routes->get('analytics/sk/attendance-consistency', 'AnalyticsController::getAttendanceConsistency');
    $routes->get('analytics/sk/popular-event-categories', 'AnalyticsController::getMostPopularEventCategories');
    $routes->get('analytics/sk/participation-by-gender', 'AnalyticsController::getParticipationByGenderPerEvent');

    // SK Document Analytics
    $routes->get('sk/document-analytics', 'AnalyticsController::skDocumentAnalytics');
    $routes->get('analytics/sk/document-categories', 'AnalyticsController::getMostAccessedDocumentCategories');
    $routes->get('analytics/sk/document-approval-time', 'AnalyticsController::getDocumentApprovalTime');
    $routes->get('analytics/sk/top-downloaded-documents', 'AnalyticsController::getTopDownloadedDocuments');

    // SK Performance Analytics
    $routes->get('sk/performance-analytics', 'AnalyticsController::skPerformanceAnalytics');
    $routes->get('analytics/sk/barangay-performance-score', 'AnalyticsController::getBarangayPerformanceScore');
    $routes->get('analytics/sk/inactive-members', 'AnalyticsController::getInactiveMembers');

    // ============================================================================
    // ADMIN DOCUMENT MANAGEMENT ROUTES
    // ============================================================================
    $routes->get('/admin/documents', 'DocumentMainController::index');
    $routes->get('/admin/documents/upload', 'DocumentMainController::upload');
    $routes->post('/admin/documents/upload', 'DocumentMainController::upload');
    $routes->get('/admin/documents/download/(:num)', 'DocumentMainController::download/$1');
    $routes->match(['GET', 'POST'], '/admin/documents/delete/(:num)', 'DocumentMainController::delete/$1');
    $routes->get('/admin/documents/edit/(:num)', 'DocumentMainController::edit/$1');
    $routes->post('/admin/documents/edit/(:num)', 'DocumentMainController::edit/$1');
    $routes->get('/admin/documents/preview/(:num)', 'DocumentMainController::preview/$1');
    $routes->get('/admin/documents/detail/(:num)', 'DocumentMainController::detail/$1');
    $routes->get('/admin/documents/audit-log', 'DocumentMainController::auditLog');
    $routes->get('/admin/documents/version-history/(:num)', 'DocumentMainController::versionHistory/$1');
    $routes->post('/admin/documents/bulk-delete', 'DocumentMainController::bulkDelete');
    $routes->post('/admin/documents/bulk-download', 'DocumentMainController::bulkDownload');
    $routes->get('/admin/documents/share/(:num)', 'DocumentMainController::share/$1');
    $routes->post('/admin/documents/share/(:num)', 'DocumentMainController::share/$1');
    $routes->get('/admin/documents/revoke-share/(:num)/(:num)', 'DocumentMainController::revokeShare/$1/$2');
    $routes->get('/admin/documents/shared', 'DocumentMainController::sharedDocuments');
    $routes->post('/admin/documents/submit-for-approval/(:num)', 'DocumentMainController::submitForApproval/$1');
    $routes->post('/admin/documents/approve/(:num)', 'DocumentMainController::approveDocument/$1');
    $routes->post('/admin/documents/reject/(:num)', 'DocumentMainController::rejectDocument/$1');
    $routes->get('/admin/documents/approval-status/(:num)', 'DocumentMainController::getApprovalStatus/$1');
    $routes->get('/admin/documents/fix-data', 'DocumentMainController::fixDocumentData');
    // Serve uploaded files through controller
    $routes->get('uploads/(.*)', 'FileController::serve/$1');

    // ============================================================================
    // ADMIN CATEGORY MANAGEMENT ROUTES
    // ============================================================================
    $routes->get('/admin/categories', 'CategoryController::index');
    $routes->get('/admin/categories/add', 'CategoryController::add');
    $routes->post('/admin/categories/add', 'CategoryController::add');
    $routes->get('/admin/categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('/admin/categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->match(['GET', 'POST'], '/admin/categories/delete/(:num)', 'CategoryController::delete/$1');

    // ============================================================================
    // UNIFIED DOCUMENT ROUTES (ALL ROLES)
    // ============================================================================
    $routes->get('/documents', 'DocumentMainController::index');
    $routes->get('/documents/detail/(:num)', 'DocumentMainController::detail/$1');
    $routes->get('/documents/api/detail/(:num)', 'DocumentMainController::apiDetail/$1');
    $routes->get('/documents/download/(:num)', 'DocumentMainController::download/$1');
    $routes->get('/documents/preview/(:num)', 'DocumentMainController::preview/$1');
    $routes->get('/documents/shared', 'DocumentMainController::sharedDocuments');

    // ============================================================================
    // ADMIN DOCUMENT MANAGEMENT ROUTES
    // ============================================================================
    $routes->get('/admin/documents', 'DocumentMainController::index');
    $routes->get('/admin/documents/upload', 'DocumentMainController::upload');
    $routes->post('/admin/documents/upload', 'DocumentMainController::upload');
    $routes->get('/admin/documents/download/(:num)', 'DocumentMainController::download/$1');
    $routes->match(['GET', 'POST'], '/admin/documents/delete/(:num)', 'DocumentMainController::delete/$1');
    $routes->get('/admin/documents/edit/(:num)', 'DocumentMainController::edit/$1');
    $routes->post('/admin/documents/edit/(:num)', 'DocumentMainController::edit/$1');
    $routes->get('/admin/documents/preview/(:num)', 'DocumentMainController::preview/$1');
    $routes->get('/admin/documents/detail/(:num)', 'DocumentMainController::detail/$1');
    $routes->get('/admin/documents/audit-log', 'DocumentMainController::auditLog');
    $routes->get('/admin/documents/version-history/(:num)', 'DocumentMainController::versionHistory/$1');
    $routes->post('/admin/documents/bulk-delete', 'DocumentMainController::bulkDelete');
    $routes->post('/admin/documents/bulk-download', 'DocumentMainController::bulkDownload');
    $routes->get('/admin/documents/share/(:num)', 'DocumentMainController::share/$1');
    $routes->post('/admin/documents/share/(:num)', 'DocumentMainController::share/$1');
    $routes->get('/admin/documents/revoke-share/(:num)/(:num)', 'DocumentMainController::revokeShare/$1/$2');
    $routes->get('/admin/documents/shared', 'DocumentMainController::sharedDocuments');
    $routes->post('/admin/documents/submit-for-approval/(:num)', 'DocumentMainController::submitForApproval/$1');
    $routes->post('/admin/documents/approve/(:num)', 'DocumentMainController::approveDocument/$1');
    $routes->post('/admin/documents/reject/(:num)', 'DocumentMainController::rejectDocument/$1');
    $routes->get('/admin/documents/approval-status/(:num)', 'DocumentMainController::getApprovalStatus/$1');
    $routes->get('/admin/documents/fix-data', 'DocumentMainController::fixDocumentData');
    $routes->get('uploads/(:any)', 'Shared\FileController::serve/$1');

    // ============================================================================
    // ADMIN CATEGORY MANAGEMENT ROUTES
    // ============================================================================
    $routes->get('/admin/categories', 'CategoryController::index');
    $routes->get('/admin/categories/add', 'CategoryController::add');
    $routes->post('/admin/categories/add', 'CategoryController::add');
    $routes->get('/admin/categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('/admin/categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->match(['GET', 'POST'], '/admin/categories/delete/(:num)', 'CategoryController::delete/$1');

    // ============================================================================
    // BULLETIN BOARD ROUTES (ALL ROLES)
    // ============================================================================
    $routes->get('/bulletin', 'BulletinController::index');
    $routes->get('/bulletin/view/(:num)', 'BulletinController::view/$1');
    // Export route removed as export feature deprecated
    
    // Bulletin Creation and Management (SK and Pederasyon only)
    $routes->get('/bulletin/create', 'BulletinController::create');
    $routes->post('/bulletin/store', 'BulletinController::store');
    $routes->get('/bulletin/edit/(:num)', 'BulletinController::edit/$1');
    $routes->post('/bulletin/update/(:num)', 'BulletinController::update/$1');
    $routes->delete('/bulletin/delete/(:num)', 'BulletinController::delete/$1');
    
    // Category Management (Pederasyon only)
    $routes->get('/bulletin/categories', 'BulletinController::categories');
    $routes->get('/bulletin/categories/list', 'BulletinController::getCategoriesList');
    $routes->get('/bulletin/categories/(:num)', 'BulletinController::getCategory/$1');
    $routes->post('/bulletin/categories/store', 'BulletinController::storeCategory');
    $routes->post('/bulletin/categories/update/(:num)', 'BulletinController::updateCategory/$1');
    $routes->delete('/bulletin/categories/delete/(:num)', 'BulletinController::deleteCategory/$1');
    
    // AJAX Routes
    $routes->get('/bulletin/category/(:any)', 'BulletinController::getPostsByCategory/$1');
    $routes->get('/bulletin/search', 'BulletinController::search');

});

