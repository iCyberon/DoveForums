<div>
    <div class="span-18">
        <h3>{thread_name}</h3>
            <div>
                <div class="span-3">
                    {first_post_avatar}
                    <p>{first_post_username}</p>
                </div>
                <div class="span-15 last">
                    <p>{first_post_content}</p>
                </div>
            </div>
            <hr />
            <div class="span-18">
                <p>Viewing x replys - Page x of x</p>
            </div>
            <hr />
            <!-- BEGIN posts -->
                <div class="span-18">
                    <div class="span-3">
                        {avatar}
                        <p>{created_by}</p>
                    </div>
                    
                    <div class="span-15 last">
                        <p>{content}</p>
                    </div>
                </div>
            <!-- END posts -->
    </div>
    <div class="span-6 last">
        <div id="forum_info" class="box">
            <h4>Forum Info</h4>
            <ul>
                <li>{forum_thread_count} topics</li>
                <li>{forum_post_count} replies</li>
                <li>Last post by: {forum_last_post_by}</li>
                <li>Last activity: {forum_last_post_activity}</li>
            </ul>
        </div>
    </div>
</div>