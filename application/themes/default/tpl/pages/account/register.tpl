<div id="register_form">
    <div class="span-17 last">
        {form_open}
            {register_fieldset}
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
                
                <p>
                    {confirm_password_label}<br />
                    {confirm_password_field}
                    {confirm_password_field_error}
                </p>
                
                <p>
                    {email_address_label}<br />
                    {email_address_field}
                    {email_address_field_error}
                </p>
                
                
                <p>
                    {confirm_email_address_label}<br />
                    {confirm_email_address_field}
                    {confirm_email_address_field_error}
                </p>
                
                {user_group_field}
                {close_fieldset}
                <hr />
                <p class="right append-bottom">
                    {register_button}
                </p>
        {form_close}
    </div>
</div>