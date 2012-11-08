<div class="span-24 last">
    {form_open}
        <h3>Reply To: {thread_name}</h3>
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
                {forum_permalink}
                {thread_permalink}
                {submit_button}
            </div>       
        {close_fieldset}
    {form_close}
</div>