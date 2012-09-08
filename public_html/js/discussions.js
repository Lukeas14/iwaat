Discussion = Backbone.Model.extend({

    defaults: {
        voted: null
    },

    test: function(){
        return 'test';
    },

    initialize: function(){
        this.bind("change:state", function(){
            var new_state = this.get('state');
            console.log('state changed to ' + new_state);
        });

        this.bind("error", function(model, error){
            console.log("error: " + error);
        });
    },

    /*validate: function(attributes){
        if(_.indexOf(['collapsed','expanded'], attributes.state) == -1){
            return 'invalid state';
        }
    },*/

    get_comment_count: function(){
        if(this.get('comments')){
            return this.get('comments').length + ' comments';
        }
        else{
            return 0;
        }
    },

    upvote: function(callback){
        if(this.get('voted') == 'up'){
            var status = false;
        }
        else{
            var status = true;
        }
        if(typeof callback == 'function') callback(status);
    },

    downvote: function(callback){

        if(typeof callback == 'function') callback();
    },

    expand: function(){
        this.set({state:'expanded'});
    },

    get_relative_time: function(time_now){
        var time_posted = this.get('time_posted');
        if(!time_posted){
            return false;
        }

        /*time_obj = new Date(time_str);
        if(time_obj == 'Invalid Date'){
            return false;
        }*/

        now_threshold = 1000 * 60 * 5; //5 minutes
        //console.log(this.get('time_posted'));
        //time_now = new Date();
        //time_delta = time_now - time_obj;
        //delta = parseInt(time_delta, 10);
        delta = time_now - this.get('time_posted')['sec'];
        delta = delta*1000;
        
        if(delta <= now_threshold){
            return "Just now";
        }

        var units = null;
        var conversions = {
            second: 1000,
            minute: 60,
            hour: 60,
            day: 24,
            month: 30,
            year: 12
        };

        for(var key in conversions){
            if(delta < conversions[key]){
                break;
            }
            else{
                units = key;
                delta = delta / conversions[key];
            }
        }

        delta = Math.floor(delta);
        if(delta !== 1) { units += 's'; }
        return [delta, units, "ago"].join(" ");
    }
    
});

Discussions = Backbone.Collection.extend({

    model: Discussion,

    base_url:'/ajax/app_discussions?app_id=',

    discussion_types:{},

    user_ids:[],

    initialize: function(models, options){
        if(typeof options.discussion_types != "undefined"){
            this.discussion_types = options.discussion_types;
        }
    },

    getDiscussions: function(callback){
        var _this = this;

        var discuss = this.fetch({
            add:true,
            dataType:'json',
            error:function(dsfsdf, modelss, errorss, sdsd){
                console.log('fetch error');
                console.log(arguments);
            },
            success:function(){
                if(typeof callback == 'function'){
                    callback();
                }
                console.log(_this.user_ids);
            }
        });
    },

    parse: function(response){
        var _this = this;

        _.each(response, function(result){
            if(typeof result.user_id !== undefined){
                _this.user_ids.push(result.user_id);
            }
        });
        return response.discussions;
    }

});

DiscussionsView = Backbone.View.extend({

    discussionViews: [],

    state: 'idle',

    fade_duration: 200,

    initialize: function(){
        var _this = this;
        this.collection.url = this.collection.base_url + this.options.app_id;
        this.collection.getDiscussions(function(){
            _this.render();
        });
    },

    events: {
        "click input#get_discussions": "getDiscussions"
    },

    getDiscussions: function(){
        var _this = this;

        if(this.state != 'idle'){
            return false;
        }

        this.state = 'getting_discussions';

        this.clearDiscussions(function(){
            _this.collection.getDiscussions(function(){
                _this.render(function(){
                    _this.state = 'idle';
                });
            });
        });
    },

    render: function(callback){
        var _this = this;
        var discussion_side = 'left'

        this.$el.find("#loading_discussions").fadeOut(this.fade_duration, function(){
            _this.$el.find("#discussions_left, #discussions_right").fadeIn();
        });

        discussion_count = this.collection.length;

        this.collection.each(function(discussion){
            view_type = _this.collection.discussion_types[discussion.get('type')].view;
            var discussionView = new window[view_type]({
                model: discussion,
                collection: _this.collection,
                id: "discussion_" + discussion.id,
                className: "discussion",
                unix_time: _this.options.unix_time,
                parentView: _this
            });
            _this.discussionViews.push(discussionView);
            _this.$el.find("#discussions_" + discussion_side).append(discussionView.$el);

            //Alternate which side of the timeline the next discussion will be placed
            discussion_side = (discussion_side == 'left') ? 'right' : 'left';

            //Execute callback when loop has ended
            if(!--discussion_count){
                 if(typeof callback == 'function'){
                    callback();
                }
            }
        });

        return this;
    },

    clearDiscussions: function(callback){
        var _this = this;

        this.discussionViews = []

        this.$el.find("#discussions_left, #discussions_right").empty().fadeOut(this.fade_duration, function(){
            //Wait till both discussion columns are hidden
            if($("#discussions_left:visible, #discussions_right:visible").length === 0){
                //Show loading discussions text
                _this.$el.find("#loading_discussions").fadeIn(this.fade_duration, function(){
                    //Launch callback
                    if(typeof callback == 'function'){
                        callback();
                    }
                });
            }
        });
        
    }

});

DiscussionView = Backbone.View.extend({

    template_id: 'discussion_template',
    template: $("#discussion_template").html(),

    initialize: function(){
        this.model.set(
            {
                comment_count: this.model.get_comment_count(),
                relative_time_posted: this.model.get_relative_time(this.options.unix_time),
                type_name: this.get_type_name(this.model.get('type')),
                text_display: this.get_text()
            }
        );
        this.model.get_comment_count();
        this.render();
    },

    events:{
        "click a.discussion_upvote": "upvote",
        "click a.discussion_downvote": "downvote",
        "click a.discussion_favorite": "favorite",
    },

    upvote: function(){
        var _this = this;

        this.model.upvote(function(){
            _this.$el.removeClass('downvoted').addClass('upvoted');
            console.log('upvoted');
        });
    },

    downvote: function(){
        var _this = this;

        this.model.downvote(function(){
            _this.$el.removeClass('upvoted').addClass('downvoted');
            console.log('downvoted');
        });
    },

    favorite: function(){
        this.model.favorite(function(status){
            console.log('favorited');
        });
    },

    render: function(){
        var discussionTemplate = _.template($("#" + this.template_id).html(), this.model.toJSON());
        this.$el.html(discussionTemplate);
    },

    get_comment_count: function(){
        return 'what';
    },

    get_type_name: function(type){
        if(!type in this.collection.discussion_types){
            return '';
        }

        return this.collection.discussion_types[type].name;
    },

});

DiscussionReviewView = DiscussionView.extend({

    template_id: "discussion_review_template",

    get_text: function(){
        var max_text_length = 400;
        var content = strip_tags(this.model.get('text'));

        return (content.length >= max_text_length) ? content.substring(0, max_text_length) + '...' : content;
    }

});


DiscussionBlogPostView = DiscussionView.extend({

    template_id: "discussion_blog_post_template",

    get_text: function(){
        var max_text_length = 400;
        var content = this.model.get('content');

        return (content.length >= max_text_length) ? content.substring(0, max_text_length) + '...' : content;
    }

});
