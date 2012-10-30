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
            <div class="span-18 last">
                <p>Viewing {post_count} replys - Page x of x</p>
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
                <p>Viewing {post_count} replys - Page x of x</p>
            </div>
    </div>
    <div class="span-6 last">
        <div id="forum_info" class="box">
            <h4>Thread Info</h4>
            <ul>
                <li>In: {forum_name}</li>
                <li>{post_count} replies</li>
                <li>Last post by: </li>
                <li>Last activity: </li>
                <li>Tags: </li>
            </ul>
        </div>
    </div>
</div>