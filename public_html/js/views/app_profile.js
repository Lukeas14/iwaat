AppProfileView = Backbone.View.extend({

	app_description_height: 0,

	initial_discussion_type: 'review',
	curr_discussion_type: {},

	initialize: function(){
		this.setDiscussionType(this.initial_discussion_type);
	},

	events: {
		"click a.app_description_expand": "toggleAppDescription",
		"mouseover button.discussion_type": "discussionTypeMouseoverHandler",
		"mouseout button.discussion_type": "discussionTypeMouseoutHandler",
		"click button.discussion_type": "discussionTypeClickHandler"
	},

	toggleAppDescription: function(elem){
		var $app_description = this.$(".app_description");
		var $expand_text = this.$("a.app_description_expand p.expand_text");

		if(this.app_description_expanded){
			this.app_description_expanded = false;

			//Collapse description div
			$app_description.animate({ height: this.app_description_height }, 500);

			$expand_text.text('Show Entire Description');
		}
		else{
			this.app_description_expanded = true;

			//Expand description div
			this.app_description_height = $app_description.height();
			auto_height = $app_description.css('height', 'auto').height();
			$app_description.height(this.app_description_height).animate({ height: auto_height }, 500);

			$expand_text.text('Hide Description');
		}
		
		return false;
	},

	discussionTypeClickHandler: function(elem){
		this.setDiscussionTypeDescription(elem.target.id);
		this.setDiscussionType(elem.target.id);
		
		return false;
	},

	discussionTypeMouseoverHandler: function(elem){
		this.setDiscussionTypeDescription(elem.target.id);
	},

	discussionTypeMouseoutHandler: function(elem){
		this.setDiscussionTypeDescription(this.curr_discussion_type.id);
	},

	setDiscussionType: function(type){
		//make sure type is valid
		if(!(type in this.collection.discussion_types)){
			return false;
		}

		this.curr_discussion_type = this.collection.discussion_types[type];

		//set input to chosen type and button to selected
		this.$("#app_add_discussion_type button").removeClass('selected');
		this.$("#app_add_discussion_type button#" + type).addClass('selected');
		this.$("form#add_discussion input[name='type']").val(type);
	},

	setDiscussionTypeDescription: function(type){
		//make sure type is valid
		if(!(type in this.collection.discussion_types)){
			return false;
		}

		discussion_type = this.collection.discussion_types[type];

		this.$("#app_discussion_type_description").text(discussion_type.description.replace("{{name}}", this.options.app.name));
	}

});