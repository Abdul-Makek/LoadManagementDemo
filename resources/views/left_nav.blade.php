<div class="left-nav">
    <ul class="main-menu">
        <li class="main-nav-item {{ Route::current()->getName() == 'grid' ? 'active' : '' }}"><a href="{{ route('grid') }}">Grids</a></li>
        <li class="main-nav-item {{ Route::current()->getName() == 'load' ? 'active' : '' }}"><a href="{{ route('load') }}">Loads</a></li>
        <li class="main-nav-item {{ Route::current()->getName() == 'report' ? 'active' : '' }}"><a href="#">Reports</a></li>
    </ul>
    <ul class="sub-menu">
        <li class="sub-nav-item {{ Route::current()->getName() == 'daily_report' ? 'active' : '' }}"><a href="{{ route('daily_report') }}">Daily Report</a></li>
    </ul>
</div>