<div>
    <div class="span-18">
        <div class="table_header">
            <h3>{forum_name}</h3>
        </div>
                <div class="table_background">
                    <table class="dove_data_grid">
                        <!-- IF {has_threads} -->
                            <!-- BEGIN threads -->
                                <tr>
                                    <td><img src="{T_Folder}/img/icons/noreplys.png" /></td>
                                    <td><strong>{title}</strong><br>Started By: {started_by}</td>
                                    <td><strong>{post_count}</strong> posts</td>
                                    <td>{last_post_avatar}</td>
                                    <td>{last_post_by}</td>
                                </tr>
                            <!-- END threads -->
                        <!-- ELSE -->
                            <tr>
                                <td class="info">Sorry no threads have been found.  Why not start one ?</td>
                            </tr>
                        <!-- END -->
                    </table>
                </div>
    </div>
    <div class="span-6 last">
        <div id="forum_info" class="box">
            <h4>Forum Info</h4>
            <ul>
                <li>{forum_thread_count} topics</li>
                <li>{forum_post_count} replies</li>
                <li>Last post by: {forum_last_post_by}</li>
                <li>Last activity: {forum_last_post_activity} ago</li>
            </ul>
        </div>
    </div>
</div>