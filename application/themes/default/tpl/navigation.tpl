<nav>
    <div class="span-24 last">
        <ul class="menu">
            <li><a href="{site_url}">Home</a></li>
            <!-- IF !logged_in -->
            <li><a href="{site_url}/account/login/">Sign In</a></li>
            <!-- ELSE -->
            <li><a href="{site_url}/account/logout">Sign Out</a></li>
            <!-- END -->
            <!-- IF is_admin -->
            <li><a href="{site_url}/admin/" rel="nofollow">Admin</a></li>
            <!-- END -->
        </ul>
    </div>
</nav>