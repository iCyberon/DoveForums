<div>
    <!-- BEGIN categories -->
        <div class="span-24 last">
            <div class="box">
                <h3>{title}</h3>
                <div>
                    <table>
                        <tr>
                            <th>Forum Name</th>
                            <th>Threads</th>
                            <th>Posts</th>
                            <th>Last Post</th>
                        </tr>
                        <!-- BEGIN forums_{id} -->
                            <tr>
                                <td><strong>{forum_title}</strong><br>{forum_content}</td>
                                <td>{thread_count}</td>
                                <td>{post_count}</td>
                                <td>{forum_last_post}</td>
                            </tr>
                        <!-- END forums_{id} -->
                    </table>
                </div>
            </div>
        </div>
    <!-- END categories -->
</div>