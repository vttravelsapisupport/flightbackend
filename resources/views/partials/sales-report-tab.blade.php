<ul class="nav nav-tabs" id="myTab" role="tablist">
    @can('sale_report show')
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request()->routeIs('sales-reports.index') ? 'active' : '' }}" href="{{ route('sales-reports.index') }}">Sales Report</a>
    </li>
    @endcan


    @can('sales-report-vendor-wise show')
    <li class="nav-item" role="presentation">
        <a class="nav-link {{request()->routeIs('sales-reports-with-vendor.index') ? 'active' : ''}}" href="{{ route('sales-reports-with-vendor.index') }}">Sales Report With Vendor</a>
    </li>
    @endcan
    @can('sales-report-agent-wise show')
    <li class="nav-item" role="">
        <a class="nav-link {{ request()->routeIs('sales-report.agent-wise') ? 'active' : ''}}" href="{{ route('sales-report.agent-wise') }}">Agent Wise </a>
    </li>
    @endcan
    @can('sales-report-sector-wise show')
    <li class="nav-item" role="presentation">
        <a class="nav-link {{request()->routeIs('sales-report.sector-wise') ? 'active' : ''}}" href="{{ route('sales-report.sector-wise') }}">Sector Wise </a>
    </li>
    @endcan
    @can('sales-report-vendor-wise show')
    <li class="nav-item" role="presentation">
        <a class="nav-link {{request()->routeIs('sales-report.vendor-wise') ? 'active' : ''}}" href="{{ route('sales-report.vendor-wise') }}">Vendor Wise </a>
    </li>
    @endcan
</ul>
