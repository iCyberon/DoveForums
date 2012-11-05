<div class="span-18">
    {form_open}
        {edit_post_fieldset}
            <p>
                {body_label}<br />
                {body_field}
                {body_field_error}
            </p>   
            
            <p>
                {tags_label}<br />
                {tags_field}
            </p>   
        {close_fieldset}
        <hr />
        {revision_fieldset}
            <p>
                {reason_label}<br />
                {reason_field}
            </p>   
            
            <div class="append-bottom">
                {submit_button}
            </div>       
        {close_fieldset}
    {form_close}
</div>