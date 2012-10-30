<div id="form">
    <div class="span-12 colborder append-bottom">
        {form_open}
            {login_fieldset}
                <p>
                    {username_label}<br />
                    {username_field}
                    {username_field_error}
                </p>
                
                <p>
                    {password_label}<br />
                    {password_field}
                    {password_field_error}
                </p>
                {close_fieldset}
                <p class="right">
                    {submit_button}
                </p>
        {form_close}
    </div>
    <div class="span-12 last">
    
    </div>
</div>