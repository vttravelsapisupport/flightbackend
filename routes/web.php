<?php

use App\Http\Controllers\FlightSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\MarkupController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\SaleRepController;
use App\Http\Controllers\SaleHeadController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ApiSupplierController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\MaintananceController;
use App\Http\Controllers\AgentPaymentController;
use App\Http\Controllers\APIRestrictionController;
use App\Http\Controllers\AgentNotificationController;
use App\Http\Controllers\BookingRequestLogController;
use App\Http\Controllers\FlightTicket\AjaxController;
use App\Http\Controllers\FlightTicket\SaleController;
use App\Http\Controllers\FlightTicket\BlockController;
use App\Http\Controllers\FlightTicket\RefundController;
use App\Http\Controllers\FlightTicket\BookingController;
use App\Http\Controllers\FlightTicket\NameListController;
use App\Http\Controllers\FlightTicket\PurchaseController;
use App\Http\Controllers\FlightTicket\PNRStatusController;
use App\Http\Controllers\AgentSupplierRestrictionController;
use App\Http\Controllers\DistributorAgentAlignmentController;
use App\Http\Controllers\FlightTicket\TicketRefundController;
use App\Http\Controllers\FlightTicket\FareManagementController;
use App\Http\Controllers\FlightTicket\Accounts\CreditsController;
use App\Http\Controllers\FlightTicket\Accounts\ReceiptController;
use App\Http\Controllers\FlightTicket\IntimationRemarksController;
use App\Http\Controllers\FlightTicket\Accounts\PnrHistoryController;
use App\Http\Controllers\FlightTicket\CancellationRequestController;
use App\Http\Controllers\FlightTicket\Reports\BlockReportController;
use App\Http\Controllers\FlightTicket\Reports\SalesReportController;
use App\Http\Controllers\FlightTicket\Accounts\AgentLedgerController;
use App\Http\Controllers\FlightTicket\Accounts\DebitorListController;
use App\Http\Controllers\FlightTicket\Reports\FlightInventorySummary;
use App\Http\Controllers\FlightTicket\Reports\InfantReportController;
use App\Http\Controllers\FlightTicket\Reports\RefundReportController;
use App\Http\Controllers\FlightTicket\Reports\TicketServiceController;
use App\Http\Controllers\FlightTicket\Accounts\CreditRequestController;
use App\Http\Controllers\FlightTicket\Accounts\DepositRequestController;
use App\Http\Controllers\FlightTicket\Accounts\SupplierLedgerController;
use App\Http\Controllers\FlightTicket\Accounts\SupplierPaymentController;
use App\Http\Controllers\FlightTicket\Accounts\OnlineTransactionController;
use App\Http\Controllers\FlightTicket\Accounts\SupplierBankDetailsController;
use App\Http\Controllers\FlightTicket\Accounts\SupplierCommissionsController;
use App\Http\Controllers\FlightTicket\Reports\SaleIntimationReportController;
use App\Http\Controllers\OrnBookingController;



Route::get('/', [LoginController::class, 'showLoginPage'])->name('login');
Route::get('/mfa', [LoginController::class, 'showMFAPage'])->name('mfa');
Route::post('/mfa', [LoginController::class, 'submitMFAPage'])->name('mfa-verification');

Route::post('/login', [LoginController::class, 'submitLoginPage']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('agent-ledger/calculation/closing-balance',[AgentLedgerController::class, 'agentLedgerClosingBalanceCalculation']);



Route::group(['middleware' => ['auth']], function () {
    Route::GET('/send-grid-ip-whitelist', [LoginController::class, 'getSendgridWhitelistIP']);
    Route::GET('/send-grid-ip-whitelist-add', [LoginController::class, 'sendgridipwhitelist']);

    Route::get('agent-ledger/calculation',[AgentLedgerController::class, 'agentLedgerCalculation']);




    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity-logs', [DashboardController::class, 'activityLogs'])->name('activity-logs');

    Route::get('/search-flight', [DashboardController::class, 'searchFlightShow'])->name('search');
    Route::get('/print-new', [DashboardController::class, 'printtwoPageShow'])->name('flight-tickets.sales');




    Route::get('/debitor-remarks', [AgentController::class, 'remarks'])->name('debitor-remarks');
    Route::get('/debitor-remarks/create', [AgentController::class, 'createRemarks'])->name('debitor-remarks.create');
    Route::post('/debitor-remarks', [AgentController::class, 'storeRemarks'])->name('debitor-remarks.store');

    Route::GET('send-from-queue', [WhatsAppController::class, 'sendMessage']);

    Route::prefix('flight-tickets')->group(function ()
    {

        Route::get('/{ticket_id}/email',[FlightSearchController::class,'sendEmail']);
        Route::get('/{ticket_id}/whatsapp',[FlightSearchController::class,'sendWhatsappTicket']);

        Route::get('/flight-search',[FlightSearchController::class,'searchFlightTicket'])->name('search_flight_tickets');
        Route::post('/airport-alignment',[AirportController::class,'airportAlignmentSubmit'])->name('airport_alignment_submit');
        Route::get('/airport-alignment',[AirportController::class,'showAirportAligmnetPage'])->name('airport_alignment');
        Route::post('/sales/comment',[SaleController::class,'submitComment']);
        Route::get('/change-password',[DashboardController::class,'changePasswordShow'])->name('change-password');
        Route::post('/change-password',[DashboardController::class,'changePasswordSubmit'])->name('change-password.store');

        Route::get('/change-password-api-agents',[DashboardController::class, 'getAgentpassword'])->name('change-password-api-agents');
        Route::post('/change-password-api-agents',[DashboardController::class, 'getApiAgentpasswordSubmit'])->name('change-password-api-agents.store');


        Route::get('/booking-request-logs',[BookingRequestLogController::class,'index']);

        Route::post('/purchase/{id}/update-acknowledge',[PurchaseController::class, 'updateAcknowledge']);

        Route::post('purchase/import', [PurchaseController::class, 'importExcel']);

        Route::get('purchase/pnr/export', [PurchaseController::class, 'excel_pnr']);
        Route::post('purchase/bulk-update', [PurchaseController::class, 'bulkUpdateOfFlightNoArrDepTime']);
        Route::get('purchase/status/{purchase_entry_id}', [PurchaseController::class, 'ViewstatusPage']);
        Route::post('purchase/status-update', [PurchaseController::class, 'submitStatusPage']);
        Route::post('purchase/sale-price-update', [PurchaseController::class, 'submitSalePriceUpdate'])->name('purchase.sale-price-update');
        Route::get('purchase/offline', [PurchaseController::class, 'PurchaseEntryShouldBeOfflineList']);
        Route::post('purchase/offline', [PurchaseController::class, 'PurchaseEntryShouldBeOfflineFormSubmit']);
        Route::post('/purchase/{id}/online-status', [PurchaseController::class, 'updateIsOnline']);
        Route::post('/purchase/{id}/refundable-status', [PurchaseController::class, 'updateIsRefundable']);
        Route::resource('purchase', PurchaseController::class);

        Route::get('/purchase-excelpreview', [PurchaseController::class, 'purchaseExcelPreview'])->name('flight-tickets.purchase');


        //Blocks
        Route::post('/blocks/release', [BlockController::class, 'releaseBlockTicket']);


        //Bookings
        Route::get('/bookings/export', [BookingController::class, 'excel']);
        Route::post('/bookings/bulk-fare-management', [BookingController::class, 'bulkFareManagement']);
        Route::resource('blocks', BlockController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('search', FlightSearchController::class);


        Route::get('/pnr-status', [PNRStatusController::class, 'index'])->name('pnr-status.index');
        Route::get('/pnr-status/{id}', [PNRStatusController::class, 'show'])->name('pnr-status.show');
        Route::post('/pnr-status', [PNRStatusController::class, 'store'])->name('pnr-status.store');


        //Sales
        Route::get('sales/print/{id}', [SaleController::class, 'print']);
        Route::get('sales/pdf/{id}', [SaleController::class, 'pdf']);
        Route::get('sales/initimation/{id}', [SaleController::class, 'initimationshow']);
        Route::post('sales/initimation/{id}', [SaleController::class, 'initimation']);
        Route::get('sales/{id}/services', [SaleController::class, 'servicePageShow']);
        Route::post('sales/{id}/services', [SaleController::class, 'servicePageSubmit']);
        Route::get('sales/trash', [SaleController::class, 'trashedItems']);
        Route::get('sales/{id}/restore', [SaleController::class, 'RestoreTrashedItems']);
        Route::get('sales/delete/{id}', [SaleController::class, 'destroy']);

        Route::get('sales/api-stock', [SaleController::class, 'apiTicketSold'])->name('sales.apiTicketSold');
        Route::resource('sales', SaleController::class);


        //ORN Booking controller
        Route::resource('orn-booking', OrnBookingController::class);


        //Ticket Service
        Route::get('ticket-services-delete', 'TicketServiceController@showDeletePage');
        Route::post('ticket-services-delete/{id}', 'TicketServiceController@submitDeleteForm');


        //Refund
        Route::resource('refunds', RefundController::class);


        Route::get('/refund-booking', [TicketRefundController::class, 'refund']);
        Route::post('/refund-booking/live-seats', [TicketRefundController::class, 'seatsLive']);
        Route::post('/refund-booking/refund', [TicketRefundController::class, 'refundBooking']);
        Route::resource('refund-ticket', TicketRefundController::class);

        // cancellations
        Route::GET('/cancellations', [CancellationRequestController::class, 'index'])->name('cancellations.index');
        // TODO
        // Route::GET('/cancellations/{id}', [CancellationRequestController::class,'show'])->name('cancellations.show');
        // Route::GET('/cancellations/edit/{id}', [CancellationRequestController::class,'update'])->name('cancellations.update');
        // Route::POST('/cancellations/edit/{id}', [CancellationRequestController::class,'updateRemarks'])->name('cancellations.update.remarks');
        // Route::POST('/cancellations/edit/{id}', [CancellationRequestController::class,'cancelRequestApproved'])->name('cancellations.update.cancel-approved');

        // cancellation Request
        Route::get('/cancel-request/{id}', [CancellationRequestController::class, 'update']);
        Route::post('/cancel-request-view', [CancellationRequestController::class, 'show']);
        Route::post('/cancel-request-remarks', [CancellationRequestController::class, 'updateRemarks'])->name('update-remarks');
        Route::post('/cancel-request-approved', [CancellationRequestController::class, 'cancelRequestApproved'])->name('request-approve');


        //NameList
        Route::get('pnr-name-list/export', [NameListController::class, 'excel'])->name('pnr-name-list.export');

        Route::get('pnr-name-list/{id}/email', [NameListController::class, 'emailNameList']);
        Route::resource('pnr-name-list', NameListController::class);


        //Ajax Data

        Route::get('/ajax/agent-remarks', [AjaxController::class, 'getAgentRemarks']);

        Route::get('/ajax/agent-credit-limit-transaction', [AjaxController::class, 'getAgentCreditLimitTransaction']);
        Route::get('/ajax/agent-credit-balance-transaction', [AjaxController::class, 'getAgentCreditBalanceTransaction']);
        Route::get('/ajax/agent-credit-transaction', [AjaxController::class, 'getAgentCreditTransaction']);
        Route::get('/ajax/agent-unflow-booking-transaction', [AjaxController::class, 'getAgentBookingUnflowTransaction']);
        Route::get('/ajax/agent-booking-transaction', [AjaxController::class, 'getAgentBookingTransaction']);
        Route::get('/ajax/passenger-details', [AjaxController::class, 'getPassengerDetailsWithPurchaseId']);
        Route::get('/ajax/search/agents', [AjaxController::class, 'searchAgents']);
        Route::get('/ajax/search/supplier', [AjaxController::class, 'searchSupplier']);
        Route::put('/ajax/intimation-report/status', [AjaxController::class, 'updateIntimationReportStatus']);
        Route::GET('/ajax/dashboard-data', [DashboardController::class, 'dashBoardData'])->name('ajax.dashboard.data');
        Route::GET('/ajax/dashboard-agents-data', [DashboardController::class, 'dashBoardAgentsData'])->name('ajax.dashboard.agents.data');
        Route::GET('/ajax/search/supplier-bank-details', [AjaxController::class, 'searchSupplierBankDetails']);
        Route::get('/ajax/agent-details', [AjaxController::class, 'getAgentDetails']);
        Route::post('/ajax/purchase-entry/send-initimation', [AjaxController::class, 'submitPurchaseEntryIntimation']);
        Route::get('/ajax/sales/{purchasentry_id}', [AjaxController::class, 'getSalesOfPurchaseEntry']);
        Route::get('/ajax/purchase/{purchasentry_id}', [AjaxController::class, 'getPurchaseEntryDetails']);
        Route::get('/ajax/agents-distributors', [AjaxController::class, 'getAgents']);
        Route::post('/ajax/debitor-remark', [AjaxController::class, 'debitorRemarkSubmit']);
        Route::get('/ajax/debtor-remarks', [AjaxController::class, 'getdebitorRemark']);
        Route::get('/ajax/agent-search', [AjaxController::class, 'getAgentBySearch']);
        Route::get('/ajax/airport-search', [AjaxController::class, 'getAirportSearch']);
        Route::POST('/ajax/infant-status-update', [AjaxController::class, 'updateInfantStatusUpdate']);
        Route::POST('/ajax/update-pax-name', [AjaxController::class, 'updatePaxName']);
        Route::POST('/ajax/last-changed-price-details', [AjaxController::class, 'lastChangedPriceDetails']);
        Route::POST('/ajax/last-changed-namelist-details', [AjaxController::class, 'lastChangedNameListDetails']);
        Route::get('/ajax/airline-details', [AjaxController::class, 'getAirlineDetails']);

        Route::post('agent/password/reset/{id}', [AgentController::class, 'resetPassword'])->name('agent.password.reset');
    
        Route::POST('/ajax/dashboard-todolist', [DashboardController::class, 'saveDashBoardTodoList'])->name('ajax.dashboard.todolist');
        Route::POST('/ajax/dashboard-todolist-update', [DashboardController::class, 'updateDashBoardTodoList'])->name('ajax.dashboard.todolist-update');
        Route::POST('/ajax/dashboard-todolist-delete', [DashboardController::class, 'deleteDashBoardTodoList'])->name('ajax.dashboard.todolist-delete');
        // searchAgents


        //Intimation report
        Route::POST('/intimation-remark', [IntimationRemarksController::class, 'submit']);


        //Faremanagement
        Route::resource('fare-management', FareManagementController::class);
    });

    Route::GET('/destination-based-on-ex/{id}', [MailerController::class, 'getDestinationBasedOnEx']);
    Route::GET('/airport-based-on-ex/{id}', [MailerController::class, 'getAirportBasedOnEx']);

    Route::prefix('notifications')->group(function () {
        Route::GET('whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');
        Route::POST('whatsapp-send', [WhatsAppController::class, 'send'])->name('whatsapp.send');
        Route::GET('mailer', [MailerController::class, 'index'])->name('mailer');
        Route::POST('mailer-send', [MailerController::class, 'sendMail'])->name('mailer.send');
        Route::GET('/destination-based-on-ex', [MailerController::class, 'getDestinationBasedOnEx']);

        Route::resource('agent-notification', AgentNotificationController::class);
    });
    Route::GET('/api-logs/vendors', [OwnerController::class, 'apiLogsVendorShow'])->name('settings.api-vendorscount');
    Route::GET('/api-logs/vendors/{id}', [OwnerController::class, 'apiLogsVendorShowResult']);


    //Temporary task
    Route::GET('calculate-agent-closing-balance', [AgentLedgerController::class, 'calculateAgentLedger']);
    Route::GET('calculate-supplier-closing-balance', [SupplierLedgerController::class, 'calculateSupplierLedger']);

    Route::get('/agent/mail/welcome/{agent_id}', [AgentController::class, 'sendEmail']);
    // settings
    Route::prefix('settings')->group(function () {
        // airports
        Route::GET('/markups', [MarkupController::class, 'showMarkupPage'])->name('markups');
        Route::GET('/airports', [AirportController::class, 'index'])->name('airports.index');
        Route::GET('/airports/create', [AirportController::class, 'create'])->name('airports.create');
        Route::GET('/airports/{id}', [AirportController::class, 'show'])->name('airports.show');
        Route::GET('/airports/edit/{id}', [AirportController::class, 'edit'])->name('airports.edit');
        Route::POST('/airports/create', [AirportController::class, 'store'])->name('airports.store');
        Route::POST('/airports/edit/{id}', [AirportController::class, 'update'])->name('airports.update');
        // airlines
        Route::GET('/airlines', [AirlineController::class, 'index'])->name('airlines.index');
        Route::GET('/airlines/create', [AirlineController::class, 'create'])->name('airlines.create');
        Route::GET('/airlines/{id}', [AirlineController::class, 'show'])->name('airlines.show');
        Route::GET('/airlines/edit/{id}', [AirlineController::class, 'edit'])->name('airlines.edit');
        Route::POST('/airlines/create', [AirlineController::class, 'store'])->name('airlines.store');
        Route::PUT('/airlines/edit/{id}', [AirlineController::class, 'update'])->name('airlines.update');
        Route::POST('/airlines/update-cancellation-policy/{id}', [AirlineController::class, 'updateCancellationPolicy'])->name('airlines.cancellation');
        Route::POST('/airlines/update-bagagge-info/{id}', [AirlineController::class, 'updateBaggageInfo'])->name('airlines.baggage-info');
        // destinations
        Route::GET('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
        Route::GET('/destinations/create', [DestinationController::class, 'create'])->name('destinations.create');
        Route::GET('/destinations/{id}', [DestinationController::class, 'show'])->name('destinations.show');
        Route::GET('/destinations/edit/{id}', [DestinationController::class, 'edit'])->name('destinations.edit');
        Route::POST('/destinations/create', [DestinationController::class, 'store'])->name('destinations.store');
        Route::PUT('/destinations/edit/{id}', [DestinationController::class, 'update'])->name('destinations.update');
        Route::POST('/destinations/update-bagagge-info/{id}', [DestinationController::class, 'updateBaggageInfo'])->name('destinations.baggage-info');
        // vendors
        Route::GET('/vendors', [OwnerController::class, 'index'])->name('vendors.index');
        Route::GET('/vendors/create', [OwnerController::class, 'create'])->name('vendors.create');
        Route::GET('/vendors/{id}', [OwnerController::class, 'show'])->name('vendors.show');
        Route::GET('/vendors/{id}/fy/{fy_id}/edit', [OwnerController::class, 'fyEditOpeningBalance'])->name('vendors.fyShowEdit');
        Route::POST('/vendors/fy-opening-balance/{id}', [OwnerController::class, 'fyUpdateOpeningBalance'])->name('vendors.FysOpeningBalanceUpdate');


        Route::GET('/vendors/edit/{id}', [OwnerController::class, 'edit'])->name('vendors.edit');
        Route::POST('/vendors/create', [OwnerController::class, 'store'])->name('vendors.store');
        Route::PUT('/vendors/edit/{id}', [OwnerController::class, 'update'])->name('vendors.update');
        Route::post('/upload-images', [OwnerController::class, 'uploadMedia']);

        // agents distributors
        Route::resource('agents', AgentController::class);
        Route::resource('distributors', DistributorController::class);
        Route::resource('agents-distributors-alignment', DistributorAgentAlignmentController::class);

        // api vendors
        Route::resource('api-vendors', ApiSupplierController::class);


        // airline markup
        Route::GET('/airline-markup', [MarkupController::class, 'index'])->name('airline-markup.index');
        Route::POST('/airline-markup', [MarkupController::class, 'store'])->name('airline-markup.store');
        Route::GET('/airline-markup/create', [MarkupController::class, 'create'])->name('airline-markup.create');
        Route::post('/update-airline-markup', [MarkupController::class, 'update'])->name('airline-markup.update');
        Route::post('/update-airline-status', [MarkupController::class, 'updateStatus'])->name('airline-markup.update.status');

        // agent wise markup
        Route::post('/markups/{id}/online-status', [MarkupController::class, 'updateIsActive']);

        // airport-aligment

        Route::post('/airport-alignment/{id}/online-status', [AirportController::class, 'updateIsActive']);


        // search restriction
        Route::GET('/search-restriction', [APIRestrictionController::class, 'index']);
        Route::GET('/search-restrictions', [APIRestrictionController::class, 'list'])->name('search-restrictions.index');
        Route::POST('/search-restriction', [APIRestrictionController::class, 'store']);
        Route::POST('/get-airline-sector-options', [APIRestrictionController::class, 'getOptions']);
        Route::POST('/update-search-restriction-status', [APIRestrictionController::class, 'updateStatus']);

        // agent supplier restrictions
        Route::resource('agent-supplier-restrictions', AgentSupplierRestrictionController::class);

        // sales head
        Route::get('/sales-head', [SaleHeadController::class, 'index'])->name('sales-head.index');
        Route::get('/sales-head/create', [SaleHeadController::class, 'create'])->name('sales-head.create');
        Route::post('/sales-head/store', [SaleHeadController::class, 'store'])->name('sales-head.store');
        Route::get('/sales-head/edit/{id}', [SaleHeadController::class, 'edit'])->name('sales-head.edit');
        Route::post('/sales-head/update', [SaleHeadController::class, 'update'])->name('sales-head.update');

        // sales rep
        Route::get('/sales-rep', [SaleRepController::class, 'index'])->name('sales-rep.index');
        Route::get('/sales-rep/create', [SaleRepController::class, 'create'])->name('sales-rep.create');
        Route::post('/sales-rep/store', [SaleRepController::class, 'store'])->name('sales-rep.store');
        Route::get('/sales-rep/edit/{id}', [SaleRepController::class, 'edit'])->name('sales-rep.edit');
        Route::post('/sales-rep/update', [SaleRepController::class, 'update'])->name('sales-rep.update');
        Route::get('/sales-rep/agent-alignment/{id}', [SaleRepController::class, 'agentAlignment'])->name('sales-rep.agent-alignment');
        Route::post('/sales-rep/agent-alignment/update', [SaleRepController::class, 'agentAlignmentUpdate'])->name('sales-rep.agent-alignment.update');
        Route::get('/sales-rep/agent-alignment/delete/{id}/{alignment_id}', [SaleRepController::class, 'agentAlignmentDelete'])->name('sales-rep.agent-alignment.delete');

        //App Settings
        Route::get('/features', [AppSettingsController::class, 'index'])->name('settings.index');
        Route::post('/features/update', [AppSettingsController::class, 'updateStatus'])->name('settings.update-status');


        //Transacting Agents
        Route::get('/show-agents', [AgentController::class, 'agentTypeReport']);
        Route::POST('agents/update-remarks', [AgentController::class, 'updateAgentsRemark']);

        // Transacting api vendors



    });


    // moderation
    Route::prefix('moderation')->group(function () {
        // otps
        Route::GET('otps', [OtpController::class, 'index'])->name('otps');
        // users
        Route::GET('/users', [UserController::class, 'index'])->name('users.index');
        Route::GET('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::GET('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::GET('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::POST('/users/create', [UserController::class, 'store'])->name('users.store');
        Route::PUT('/users/edit/{id}', [UserController::class, 'update'])->name('users.update');
        Route::POST('/users/{id}', [UserController::class, 'update_password'])->name('users.update.password');
        // roles
        Route::GET('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::GET('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::GET('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::GET('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
        Route::POST('/roles/create', [RoleController::class, 'store'])->name('roles.store');
        Route::PUT('/roles/edit/{id}', [RoleController::class, 'update'])->name('roles.update');
        // permissions
        Route::GET('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::GET('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::GET('/permissions/{id}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::GET('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::PUT('/permissions/edit/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::POST('/permissions/create', [PermissionController::class, 'store'])->name('permissions.store');
    });



    Route::prefix('accounts')->group(function () {
        // pnr history
        Route::POST('/pnr-history/import', [PnrHistoryController::class, 'importExcel'])->name('pnr-history.import');
        Route::POST('/pnr-history/update', [PnrHistoryController::class, 'addRemarks']);

        // pnr history
        Route::GET('/pnr-history/show/{id}', [PnrHistoryController::class, 'showPnrHistory']);
        Route::resource('/pnr-history', PnrHistoryController::class);

        Route::get('/excel-preview', [PnrHistoryController::class, 'excelPreview'])->name('accounts.pnr-history.excelPreview');


        // credits debits
        Route::get('credits-debits/history', [CreditsController::class,'history'])->name('credits-debits.history');
        Route::resource('credits-debits', CreditsController::class);

        // agent ledger
        Route::GET('agent-ledger', [AgentLedgerController::class, 'index'])->name('agent-ledger.index');

        //Supplier Ledger
        Route::GET('supplier-ledger', [SupplierLedgerController::class, 'index'])->name('supplier-ledger.index');
        Route::GET('supplier-ledger/export', [SupplierLedgerController::class, 'excel_agent_ledger']);
        Route::GET('api-vendor-ledger', [SupplierLedgerController::class, 'getApiVendorLedger'])->name('supplier-ledger.api');
        Route::GET('api-vendor-ledger/export', [SupplierLedgerController::class, 'excel_vendor_ledger']);

        //Distributor Ledger
        Route::GET('distributor-ledger', [AgentLedgerController::class, 'showDistributorLedgerPage'])->name('distributor-ledger.index');

        //RECEIPTS
        Route::post('receipts/update/{id}', [ReceiptController::class, 'updateReceiptStatus']);
        Route::resource('agent-receipts', ReceiptController::class);
        Route::resource('agent-payments', AgentPaymentController::class);

        // payments
        Route::resource('supplier-payments', SupplierPaymentController::class);
        Route::resource('supplier-commissions', SupplierCommissionsController::class);
        Route::resource('supplier-bank-details', SupplierBankDetailsController::class);

        // ONLINE TRANSACTIONS
        Route::resource('online-transactions', OnlineTransactionController::class);


        // DEPOSIT REQUEST
        Route::resource('deposit-requests', DepositRequestController::class);


        // CREDIT REQUEST
        Route::GET('credit-requests', [CreditRequestController::class, 'index'])->name('credit-requests');
        Route::post('/credit-requests/{id}', [CreditRequestController::class, 'update']);
        Route::post('/credit-requests-remarks', [CreditRequestController::class, 'updateRemarks'])->name('update-credit-remarks');
        //Debitor list
        Route::resource('/debitor-list', DebitorListController::class);
    });



    Route::prefix('reports')->group(function () {
        //Sale Intimation Report
        Route::GET('sales-reports/agent-wise', [SalesReportController::class, 'AgentWise'])->name('sales-report.agent-wise');
        Route::GET('sales-reports/vendor-wise', [SalesReportController::class, 'VendorWise'])->name('sales-report.vendor-wise');
        Route::GET('sales-reports/sector-wise', [SalesReportController::class, 'SectorWise'])->name('sales-report.sector-wise');
        Route::GET('intimation-reports', [SaleIntimationReportController::class, 'index'])->name('intimation-reports.index');


        // infant reports
        Route::GET('infant-reports', [InfantReportController::class, 'index'])->name('infant-reports.index');

        // sales reports
        Route::GET('sales-reports', [SalesReportController::class, 'index'])->name('sales-reports.index');
        Route::GET('sales-reports/export', [SalesReportController::class, 'excel'])->name('sales-reports.export');

        //sales report with vendor
        Route::GET('sales-reports-with-vendor', [SalesReportController::class, 'vendorShow'])->name('sales-reports-with-vendor.index');


        // refund reports
        Route::GET('refund-reports', [RefundReportController::class, 'index'])->name('refund-reports.index');
        Route::GET('refund-reports/export', [RefundReportController::class, 'excel'])->name('refund-reports.export');

         //refund report with vendor
         Route::GET('refund-reports-with-vendor', [SalesReportController::class, 'refundShow'])->name('refund-reports-with-vendor.index');

        // block reports
        Route::GET('block-reports', [BlockReportController::class, 'index'])->name('block-reports.index');


        // ticket service reports
        Route::GET('ticket-service-reports', [TicketServiceController::class, 'index'])->name('ticket-service-reports.index');
        Route::POST('delete-infant-charge', [TicketServiceController::class, 'deleteDuplicateInfantCharges'])->name('delete-infant-charge');

        Route::GET('flight-inventory-summary', [FlightInventorySummary::class, 'index'])->name('flight-inventory-summary.index');
    });

    # Maintanance Module

    Route::prefix('maintanance')->group(function () {
        Route::GET('manage-refunds', [MaintananceController::class, 'ManageRefunds'])->name('manage-refunds');
        Route::GET('/manage-refund-details/{id}/{ticket_id}/{ceated_at}', [MaintananceController::class, 'ManageRefundDetails'])->name('manage-refund-details');

        Route::GET('/manage-duplicate-booking-details', [MaintananceController::class, 'ManageDuplicateBookingDetails'])->name('manage-duplicate-booking-details');
        Route::GET('/manage-duplicate-booking-details-list/{bill_no}', [MaintananceController::class, 'ManageDuplicateBookingDetailsList'])->name('manage-duplicate-booking-details-list');

        Route::GET('manage-agent-balance-calculations', [MaintananceController::class, 'ManageAgentBalanceCalculations'])->name('manage-agent-balance-calculations');
        Route::GET('manage-supplier-balance-calculations', [MaintananceController::class, 'ManageSupplierBalanceCalculations'])->name('manage-supplier-balance-calculations');
        Route::GET('manage-account-transactions', [MaintananceController::class, 'ManageAccountTransactions'])->name('manage-account-transactions');

        Route::POST('/manage-delete-refunds', [MaintananceController::class, 'AjaxDeleteRefunds'])->name('manage-delete-refunds');

        Route::GET('/manage-infant-report', [MaintananceController::class, 'ManageInfantReport'])->name('manage-infant-report');

        Route::GET('/manage-pnr-history', [MaintananceController::class, 'ManagePnrHistory'])->name('manage-pnr-history');

        Route::GET('/manage-closing-balance', [MaintananceController::class, 'CalculateClosingBalance'])->name('manage-closing-balance');

        Route::GET('/manage-restore-points', [MaintananceController::class, 'GetRestorePoints'])->name('manage-restore-points');

        Route::POST('/manage-restore-operation', [MaintananceController::class, 'AjaxRunRestoreOperation'])->name('manage-restore-operation');
        Route::POST('/manage-delete-restore-points', [MaintananceController::class, 'AjaxDeleteRestorePoints'])->name('manage-delete-restore-points');

        Route::GET('/manage-resync-account-transactions', [MaintananceController::class, 'ResyncAccountTransactions'])->name('manage-resync-account-transactions');

        Route::GET('/update-agent-balance', [MaintananceController::class, 'UpdateAgentBalance'])->name('update-agent-balance');
        Route::GET('/update-account_transaction', [MaintananceController::class, 'UpdateAT'])->name('update-account_transaction');
        //Route::GET('/get-agent-chunks', [MaintananceController::class, 'UpdateAT'])->name('update-account_transaction');
    });
    ## ajax request
    Route::POST('/ajax/agent-markup',[AjaxController::class,'setupAgentMarkupConfig']);
    Route::get('/ajax/search/airports',[AjaxController::class,'searchAirport']);
    Route::POST('/ajax/markup-global-config',[AjaxController::class,'updateMarkupGlobalConfig']);

    Route::resource('api_keys', App\Http\Controllers\ApiKeyController::class);


});

Route::GET('/sendgrid-settings', [MaintananceController::class, 'updateSengridSettings']);
