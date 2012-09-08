<script id='discussion_blog_post_template' type='text/template'>
    <div class="discussion_header">
        <a class="discussion_title" href="<%= link %>" target="_blank" rel="nofollow"><%= title %></a>
        <p class="discussion_type"><%= type_name %></p>
        <p class="discussion_time"><%= relative_time_posted %></p>
    </div>
    
    <p class="discussion_text">
        <%= text_display %>
    </p>

    <div class="discussion_footer">
        <p class="discussion_score">23</p>
        <p class="discussion_comment_count">1</p>
    </div>

    <!--
    <a class="discussion_upvote" href="javascript:void(0)">Up Vote</a>
    <a class="discussion_downvote" href="javascript:void(0)">Down Vote</a>
    <a class="discussion_favorite" href="javascript:void(0)">Favorite</a>
    -->

    <div class='timeline_line'><div class="timeline_dot"></div></div>
</script>

<script id='discussion_review_template' type='text/template'>
    <div class="discussion_header">
        <img class="user_avatar" src="" />
        <a href="/user/" class="discussion_username"></a>
        <a class="discussion_title" href="" target="_blank"><%= title %></a>
        <span class="discussion_type"><%= type_name %></span>
        <span class="discussion_time"><%= relative_time_posted %></span>
    </div>

    <p class="discussion_text">
        <%= text_display %>
        <a href="/review/<?=$app['slug']?>/" target="_blank" rel="nofollow">Read More</a>
    </p>

    <div class="discussion_footer">
        <p class="discussion_score"><%= score %></p>
        <p class="discussion_comment_count"><%= comment_count %></p>
    </div>

    <!--
    <a class="discussion_upvote" href="javascript:void(0)">Up Vote</a>
    <a class="discussion_downvote" href="javascript:void(0)">Down Vote</a>
    <a class="discussion_favorite" href="javascript:void(0)">Favorite</a>
    -->

    <div class='timeline_line'><div class="timeline_dot"></div></div>
</script>