<ul class="nav">
    <li class="{{ request()->is('/dashboard') ? 'active' : '' }}">
        <a href="/dashboard">
            <i class="nc-icon nc-bank"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="{{ request()->is('monitoring-makan*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="nc-icon nc-diamond"></i>
            <p>Monitoring Makan</p>
        </a>
        <ul class="submenu">
            <li class="{{ request()->is('/monitoring-makan/ayam-kecil') ? 'active' : '' }}">
                <a href="/monitoring-makan/ayam-kecil">Ayam kecil</a>
            </li>
            <li><a href="/monitoring-makan/ayam-sedang">Ayam Sedang</a></li>
            <li><a href="/monitoring-makan/ayam-besar">Ayam Besar</a></li>
        </ul>
    </li>
    <li class="{{ request()->is('/monitoring-minum') ? 'active' : '' }}">
        <a href="/monitoring-minum">
            <i class="nc-icon nc-pin-3"></i>
            <p>Minum</p>
        </a>
    </li>
    <li class="{{ request()->is('/monitoring-suhu') ? 'active' : '' }}">
        <a href="/monitoring-suhu">
            <i class="nc-icon nc-bell-55"></i>
            <p>Suhu dan Kelembapan</p>
        </a>
    </li>

    @if (!session()->has('user') || (session()->has('user') && session('user')['roles'] !== 'pegawai'))
    <li class="{{ request()->is('pegawai') ? 'active' : '' }}">
        <a href="/pegawai">
            <i class="nc-icon nc-single-02"></i>
            <p>Data Pegawai</p>
        </a>
    </li>
@endif

    <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>

</ul>

</div>
</div>
<div class="main-panel">
