<div>
    <div class="span-18">
        <h3>{thread_name}</h3>
            <div class="span-18 last">
                <p>Viewing {post_count} replys - {pagination}</p>
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
            <hr />
            <div class="span-18 last">
                <p>Viewing {post_count} replys - {pagination}</p>
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
</div>