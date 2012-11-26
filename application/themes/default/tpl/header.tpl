<div id="header_wrap">
    <header>
        <div class="span-18">
            <h1 class="logo">
                <a href="{site_url}">{site_title}</a>
            </h1>
        </div>
            <div class="span-6 login_box right last">
                <ul id="top-menu" class="right">
                    <li><a href="#"><img src="{T_Folder}/img/icons/small-cog-white.png" class="icon" />Account</a>
                        <ul>
                            <!-- IF !logged_in -->
                            <li>{login_link}</li>
                            <li>{register_link}</li>
                            <!-- ELSE -->
                            <li>{manage_account_link}</li>
                            <li>{logout_link}</li>
                            <!-- END -->
                        </ul>
                    </li>
                </ul>
            </div>
    </header>
</div>