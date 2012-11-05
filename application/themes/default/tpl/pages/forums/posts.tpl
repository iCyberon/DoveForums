<div>
    <div class="span-18">
        <div class="box">
        <h3>{thread_name}</h3>
        
            <!-- BEGIN posts -->
                <div class="span-17">
                    <div class="span-3">
                        {avatar}
                    </div>
                    
                    <div class="span-14 last " id="{id}">
                        <div class="append-bottom"><strong>{created_by}</strong><span class="right">{posted} / {post_permalink}</span></div>
                        <p>{content}</p>
                        <p>{edit_link}&nbsp;{delete_link}&nbsp;{spam_link}</p>
                    </div>
                </div>
            <!-- END posts -->
            <div class="clear"></div>
        </div>
            <hr />
            <div class="span-14 pagination blue append-bottom">
                {pagination}
            </div>
        
        </div>
    <div class="span-6 last">
        <div id="forum_info" class="box">
            <h4>Thread Info</h4>
            <ul>
                <li>In: {forum_name}</li>
                <li>{post_count} replies</li>
                <li>Last post by: {thread_last_post_by}</li>
                <li>Last activity: {thread_last_activity} ago</li>
                <li>Tags: </li>
                <!-- IF is_admin -->
                    <li>{stick_thread}</li>
                    <li>{thread_status}</li>
                <!-- END -->
            </ul>
        </div>
    </div>
    <div class="span-18">
        <!-- IF logged_in -->
            <!-- IF thread_open -->
            {form_open}
                {create_discussion_fieldset}

                <p>
                    {body_label}<br />
                    {body_field}
                    {body_field_error}
                </p>
                
                <p>
                    {tags_label}<br />
                    {tags_field}
                </p>

                <div class="append-bottom">
                    {submit_button}
                </div>
                
                {close_fieldset}
            {form_close}
            <!-- ELSE -->
                <div class="info">
                    <span>This thread is closed for posting.</span>
                </div>
            <!-- END -->
        <!-- ELSE -->
            <div class="info">
                <span>You need to be logged in to reply to this thread.</span>
            </div>
        <!-- END -->
    </div>
</div>