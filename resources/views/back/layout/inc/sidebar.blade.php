<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home fa-sm"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all-clients') }}">
                        <i class="fas fa-users fa-sm"></i>
                        <span data-key="t-cart">Clients</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all-invoices') }}">
                        <i class="fas fa-file-invoice fa-sm"></i>
                        <span data-key="t-cart">Invoice</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all-challans') }}">
                        <i class="fas fa-truck fa-sm"></i>
                        <span data-key="t-cart">Delivery Challans</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all-payment-history') }}">
                        <i class="fas fa-file-invoice-dollar fa-sm"></i>
                        <span data-key="t-cart">Payment History</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog fa-sm"></i>
                        <span data-key="t-cart">Settings</span>
                    </a>
                </li>
            </ul>


        </div>
        <!-- Sidebar -->
    </div>
</div>
