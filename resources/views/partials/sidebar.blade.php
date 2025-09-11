
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic">
                <i class="mdi mdi-file-account menu-icon"></i>
                <span class="menu-title">Accounts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic3">
                <ul class="nav flex-column sub-menu">

                    @can('pnr_history show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('pnr-history.index') }}">PNR History</a></li>
                    @endcan
                    @can('credit access')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('credits-debits.index') }}">Credits/Debits</a></li>
                    @endcan
                    @can('agent_ledger show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agent-ledger.index') }}">Agent Ledger</a></li>
                    @endcan
                    @can('supplier-ledger show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('supplier-ledger.index') }}">Supplier Ledger</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('supplier-ledger.api') }}">API Vendor Ledger</a></li>
                    @endcan
                    @can('distributor-ledger show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('distributor-ledger.index') }}">Distributor Ledger</a></li>
                    @endcan
                    @can('receipt show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agent-receipts.index') }}">Agent Receipts</a></li>
                    @endcan
                    @can('receipt show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agent-payments.index') }}">Agent Payments</a></li>
                    @endcan
                    @can('supplier_payment show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('supplier-payments.index') }}">Supplier Payments</a></li>
                    @endcan
                    @can('supplier_commission show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('supplier-commissions.index') }}">Supplier Commissions</a></li>
                    @endcan
                    @can('online-transaction show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('online-transactions.index') }}">Online Transactions</a></li>
                    @endcan
                    @can('deposit-request show')
                        @php
                            $user = Auth::user();
                            $dr_pending = $user->pendingDepositRequest()
                        @endphp
                        <li class="nav-item"> <a class="nav-link" href="{{ route('deposit-requests.index') }}">Deposit Requests
                                <span class="badge badge-light">{{ $dr_pending }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('credit-request show')
                        @php
                            $user = Auth::user();
                            $cr_pending = $user->pendingCreditRequest()
                        @endphp
                        <li class="nav-item"> <a class="nav-link" href="{{ route('credit-requests') }}">Credit Requests
                            <span class="badge badge-light">{{ $cr_pending }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('debitor-list show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('debitor-list.index') }}">Debitor List</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#flight_tickets" aria-expanded="false"
               aria-controls="ui-advanced">
                <i class="mdi mdi-ticket menu-icon"></i>
                <span class="menu-title">Flight Tickets</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="flight_tickets">
                <ul class="nav flex-column sub-menu">
                    @can('purchase_entry show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('purchase.index') }}">Purchase</a>
                        </li>
                    @endcan
                    @can('book_ticket show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">Bookings</a>
                        </li>
                    @endcan

                    @can('fare_management show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('fare-management.index') }}">Fare Management</a>
                        </li>
                    @endcan
                    @can('sold_ticket show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.index') }}">Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orn-booking.index') }}">ORN Confirmed Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.apiTicketSold') }}">API Vendor Sales</a>
                        </li>
                    @endcan
                    @can('refunds show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('refunds.index') }}">Refunds</a>
                        </li>
                    @endcan
                    @can('namelist show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pnr-status.index') }}">PNR Status</a>
                        </li>
                    @endcan
                    @can('namelist show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pnr-name-list.index') }}">PNR Name List</a>
                        </li>
                    @endcan
                    @can('flight-cancellation show')
                        @php
                            $user = Auth::user();
                            $cl_pending = $user->pendingCancelRequest()
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cancellations.index') }}">Cancellations
                                <span class="badge badge-light">{{ $cl_pending }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false"
               aria-controls="ui-advanced">
                <i class="mdi mdi-file-chart menu-icon"></i>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="reports">
                <ul class="nav flex-column sub-menu">
                    @can('sales-report-agent-wise show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-report.agent-wise') }}">Agent Wise Sale Report </a></li>
                    @endcan
                    @can('sales-report-sector-wise show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-report.sector-wise') }}">Sector Wise Sale Report </a></li>
                    @endcan
                    @can('sales-report-vendor-wise show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-report.vendor-wise') }}">Vendor Wise Sale Report </a></li>
                    @endcan
                    @can('sale-intimation-reports show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('intimation-reports.index') }}">Intimation Report</a></li>
                    @endcan
                    @can('sales-report-vendor-wise show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-reports-with-vendor.index') }}">Sales Report with Vendor</a></li>
                    @endcan
                    @can('sales-report-vendor-wise show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('refund-reports-with-vendor.index') }}">Refund Report with Vendor</a></li>
                    @endcan
                    @can('infant_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('infant-reports.index') }}">Infant Report</a></li>
                    @endcan
                    @can('sale_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-reports.index') }}">Sale Report</a></li>
                    @endcan
                    @can('refund_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('refund-reports.index') }}">Refund Report</a></li>
                    @endcan
                    @can('block_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('block-reports.index') }}">Block Report</a></li>
                    @endcan
                    @can('ticket_service_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('ticket-service-reports.index') }}">Ticket Service Report</a></li>
                    @endcan
                    @can('flight_inventory_summary_report show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('flight-inventory-summary.index') }}">Flight Inventory Summary</a></li>
                    @endcan
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#notifications" aria-expanded="false" aria-controls="ui-basic">
                <i class="mdi mdi-bell menu-icon"></i>
                <span class="menu-title">Notifications</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="notifications">
                <ul class="nav flex-column sub-menu">
                    @can('agent-notification show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agent-notification.index') }}">Agent Notification</a></li>
                    @endcan
                    @can('mailer-notification show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('mailer') }}">Mailer</a></li>
                    @endcan
                    @can('mailer-notification show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('whatsapp') }}">WhatsApp</a></li>
                    @endcan
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false"
               aria-controls="ui-advanced">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                  
                    @can('airport show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('airports.index') }}">Airports1</a></li>
                    @endcan
                    @can('airline show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('airlines.index') }}">Airlines</a></li>
                    @endcan
                    @can('destination show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('destinations.index') }}">Destinations</a></li>
                    @endcan
                    @can('owner show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('vendors.index') }}">Vendors</a></li>
                    @endcan
                    @can('agent show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agents.index') }}">Agents</a></li>
                    @endcan
                    @can('distributor show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('distributors.index') }}">Distributors</a></li>
                    @endcan
                    <li class="nav-item"><a class="nav-link" href="{{ route('api-vendors.index') }}">API Vendors</a></li>
                    @can('airline-markup show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('airline-markup.index') }}">Airline Markup</a></li>
                    @endcan
                    @can('airline-sector-restriction show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('search-restrictions.index') }}">Search Restrictions</a></li>
                    @endcan
                    @can('agent_supplier_restriction show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('agent-supplier-restrictions.index') }}">Agent Supplier Restriction</a></li>
                    @endcan
                    @can('sales_reps show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-head.index') }}">Sale Head</a></li>
                    @endcan
                    @can('sales_reps show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('sales-rep.index') }}">Sale Rep</a></li>
                    @endcan
                    @can('settings show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('settings.index') }}">Features</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#moderation" aria-expanded="false"
               aria-controls="ui-advanced">
                <i class="mdi mdi-account-settings menu-icon"></i>
                <span class="menu-title">Moderation</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="moderation">
                <ul class="nav flex-column sub-menu">
                    @can('otp show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('otps') }}">OTPs</a></li>
                    @endcan
                    @can('users show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    @endcan
                    @can('role show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>
                    @endcan
                    @can('permission show')
                        <li class="nav-item"><a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        {{-- Super Admin Area For Maintenance Module --}}

        @can('maintanance-module')
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#maintenance" aria-expanded="false" aria-controls="ui-advanced">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Maintenance</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="maintenance">
                <ul class="nav flex-column sub-menu">
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-refunds') }}">Refunds</a></li> -->
                    <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-resync-account-transactions') }}">Resync Agent Transactions</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-agent-balance-calculations') }}">Agent Balance Calculations</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-supplier-balance-calculations') }}">Supplier Balance Calculations</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-duplicate-booking-details') }}">Duplicate Booking Details</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-infant-report') }}">Infant Report</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-pnr-history') }}">PNR History</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" target="_blank" href="{{ route('manage-restore-points') }}">Restore Points</a></li> -->
                </ul>
            </div>
        </li>
        @endcan
    </ul>
</nav>
