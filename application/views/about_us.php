<?php $this->load->view('includes/header'); ?>

<div id="about_us_wrapper">

	<h1>About Us</h1>
	
	<div id="about_team">
		<img src="/images/justin_lucas.jpg"/>
		<p class="team_name">
			<b>Justin Lucas - Founder</b>
		</p>
		<p>
			IWAAT.com is the side project of web developer and wannabe entrepreneur Justin Lucas.
			What began as a weekend goal to index the <a href="http://www.crunchbase.com">Crunchbase</a> database has slowly expanded into the site IWAAT.com is today.
			
		</p>
	</div>
	
	<p>
		I Want An App That... (IWAAT.com) is a web application discovery engine.
		Our mission is to help you discover and research apps that you may never come across otherwise.
		At the same time, we hope to provide application creators with increased visibility to potential users.
	</p>
		
	<p id="about_discover" class="about_condensed">
		<span>Discover:</span> Over the past decade, the number of web applications has exploded as developers create online solutions for every imaginable niche.
		However, as that number grows, your ability to find the exact application you need amongst the thousands in existence is getting harder.
		We've taken the first step to solving this problem by curating a database of web apps and then using it to build a search engine and directory.
		Search for applications by a specific problem you're trying to solve, browse apps by category, or find apps related to the ones you already use.
	</p>

	<p id="about_research" class="about_condensed">
		<span>Research:</span> Ok, so now you've found several web applications that all look like may fit your need.
		But in order to make your decision you now need to register and try out each one individually.
		We've done the hard work of aggregating essential information on each application to reduce the amount of time you spend on research.
		On each application's page you'll find a full description, screenshots, important URLs (homepage, blog, RSS) and the their latest blog and Twitter entries.
		We've also assigned every application a Traction Index, a score ranging from 1 to 100 measuring an app's online popularity.
	</p>
	
	<p id="about_technology" class="about_condensed">
		<span>Technology:</span> Powering IWAAT.com is a constantly updating web index. 
		Throughout the day our servers are busy gathering data from across the web by crawling site content, retrieving each app's social and blog communications, and parsing what others say about them within social networks and the tech media.
		These bits and pieces of information are then thrown into an algorithm that creates the search and traction indexes.
	</p>
	
	<p>
		We're currently hard at work creating the next set of features designed to improve app discovery and research on IWAAT.com.
		An effort is underway to build a social side to the site, giving users the ability to interact with each other and app creators.
		Improving search result relevance is also a top priority as we continuously iterate on our data gathering and indexing techniques.
		
		<br/><br/>
		Be the first to know about and gain access to new features by signing up for our private beta using the form below.
	</p>

</div>

<?php $this->load->view('includes/footer'); ?>