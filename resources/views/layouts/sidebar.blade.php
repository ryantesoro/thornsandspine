<div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.order.*') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">
                        <i data-feather="shopping-bag"></i>
                        Orders
                    </a>
                </li>
                @if(auth()->user()->access_level == 2)
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.product.*') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">
                        <i data-feather="package"></i>
                        Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.pot.*') ? 'active' : '' }}" href="{{ route('admin.pot.index') }}">
                        <i data-feather="package"></i>
                        Pots
                    </a>
                </li>
                @endif
                @if(auth()->user()->access_level == 2)
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.customer.*') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">
                        <i data-feather="users"></i>
                        Customers
                    </a>
                </li>
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Sales</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.sales.index') ? 'active' : '' }}" href="{{ route('admin.sales.index') }}">
                        <i data-feather="dollar-sign"></i>
                        Order Sales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.sales.product') ? 'active' : '' }}" href="{{ route('admin.sales.product') }}">
                        <i data-feather="dollar-sign"></i>
                        Product Sales
                    </a>
                </li>
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Shipping</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.courier.*') ? 'active' : '' }}" href="{{ route('admin.courier.index') }}">
                        <i data-feather="truck"></i>
                        Shipping Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.shipping_fee.*') ? 'active' : '' }}" href="{{ route('admin.shipping_fee.index') }}">
                        <i data-feather="truck"></i>
                        Shipping Fees
                    </a>
                </li>
                @endif
            </ul>

            @if(auth()->user()->access_level == 2)
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Miscellaneous</span>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.config.*') ? 'active' : '' }}" href="{{ route('admin.config.index') }}">
                        <i data-feather="settings"></i>
                        Configurations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.province.*') ? 'active' : '' }}" href="{{ route('admin.province.index') }}">
                        <i data-feather="map"></i>
                        Provinces
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.city.*') ? 'active' : '' }}" href="{{ route('admin.city.index') }}">
                        <i data-feather="map-pin"></i>
                        Cities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.faq.*') ? 'active' : '' }}" href="{{ route('admin.faq.index') }}">
                        <i data-feather="help-circle"></i>
                        FAQs
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </nav>
</div>