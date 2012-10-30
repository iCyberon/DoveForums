<div>
    <div class="span-18">
        <div class="box">
            <h3>{forum_name}</h3>
            <div>
                <table>
                    <tr>
                        <th>Topic</th>
                        <th>Replies</th>
                        <th>Last Post</th>
                    </tr>
                    <!-- BEGIN threads -->
                        <tr>
                            <td><strong>{title}</strong><br>Started By: {started_by}</td>
                            <td>{post_count}</td>
                            <td>{last_activity}<br>{last_post_by}</td>
                            </tr>
                        <!-- END threads -->
                    </table>
                </div>
            </div>
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