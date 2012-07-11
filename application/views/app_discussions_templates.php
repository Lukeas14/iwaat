
<script id='discussion_blog_post_template' type='text/template'>
    <div class="discussion_header">
        <a class="discussion_title" href="<%= link %>" target="_blank" rel="nofollow"><%= title %></a>
        <p class="discussion_type"><%= type_display %></p>
        <p class="discussion_time"><%= relative_time_posted %></p>
    </div>
    
    <p class="discussion_text"><%= content %></p>

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
        <img class="user_avatar" src="<%= user_avatar_url %>" />
        <a href="/user/<%= user_slug %>" class="discussion_username"><%= username %></a>
        <span class="discussion_type"><%= type_display %></span>
        <span class="discussion_time"><%= relative_time_posted %></span>
    </div>

    <div class="discussion_title"><%= title %></div>
    <p class="discussion_text"><%= text %></p>

    <div class="discussion_footer">
        <p class="discussion_score"><%= vote_score %></p>
        <p class="discussion_comment_count"><%= comment_count %></p>
    </div>

    <!--
    <a class="discussion_upvote" href="javascript:void(0)">Up Vote</a>
    <a class="discussion_downvote" href="javascript:void(0)">Down Vote</a>
    <a class="discussion_favorite" href="javascript:void(0)">Favorite</a>
    -->

    <div class='timeline_line'><div class="timeline_dot"></div></div>
</script>