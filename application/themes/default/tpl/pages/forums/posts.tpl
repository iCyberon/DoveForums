<div>
    <div class="span-18">
        <h3>{thread_name}</h3>
            <div class="span-18 last">
                <p>Viewing {post_count} replys</p>
            </div>
            <hr />
            <!-- BEGIN posts -->
                <div class="span-18">
                    <div class="span-3">
                        {avatar}
                    </div>
                    
                    <div class="span-15 last " id="{id}">
                        <div class="append-bottom"><strong>{created_by}</strong><span class="right">{posted} / {post_permalink}</span></div>
                        <p>{content}</p>
                    </div>
                </div>
            <!-- END posts -->
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
            </ul>
        </div>
    </div>
    <div class="span-18">
        <!-- IF logged_in -->
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
                <span>You need to be logged in to reply to this thread.</span>
            </div>
        <!-- END -->
    </div>
</div>