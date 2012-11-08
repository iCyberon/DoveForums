<header>
    <div id="header-wrap">
        <div class="span-18">
            <h1 class="logo">
                <a href="{site_url}">{site_title}</a>
            </h1>
        </div>
        <div class="span-6 last" id="login_box">
            <div class="span-1">
                {avatar}
            </div>
            <div class="span-3 last">
            <!-- IF !logged_in -->
                {lang: text_welcome}&nbsp;{active_user}<br />
                {login_link}&nbsp;|&nbsp;{register_link}
            <!-- ELSE -->
                {lang: text_welcome}&nbsp;{active_user}<br />
                {logout_link}&nbsp;|&nbsp;{manage_account_link}
            <!-- END -->
            </div>
        </div>
    </div>
</header>