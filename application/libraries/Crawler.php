<?php 

require_once(BASE_DIR . '/application/libraries/Simple_html_dom.php');

class Crawler{
	
	const USER_AGENT = "IWAATbot";
	
	public $url;
	public $url_data;

	public $dom;
	private $xpath;
	private $text;
	private $links;
	private $robots_rules;

	function __construct(){
		$this->set_user_agent(self::USER_AGENT);
		$this->dom = new simple_html_dom();
	}
	
	public function set_user_agent($user_agent){
		ini_set('user_agent', $user_agent);
	}

	public function check_url($url){
		$headers = @get_headers($url, 0);
		if(is_array($headers)){
			if(strpos($headers[0], '404')){
				return false;
			}
			
			foreach($headers as $header){
				if(strpos($header, '404 Not Found')){
					return false;
				}
			}
			
			return true;
		}
		else{
			return false;
		}
		
	}
	
	public function set_url($url){
		$this->url = $url;
		
		if((strpos($url, 'http')) === false) $url = 'http://' . $url;
		
		if($this->check_url($url) === false){
			return false;
		}
		
		if($this->dom->load_file($url) === false){
			return false;
		}

		$this->url_data = parse_url($url);
		if(empty($this->url_data['scheme'])){
			$this->data['scheme'] == 'http';
		}
		$this->url_data['domain'] = implode(".", array_slice(explode(".", $this->url_data['host']), -2));
		
		if(empty($this->url_data['path']) || $this->url_data['path'] != '/robots.txt'){
			$this->get_robots();
		}
		
		return true;
	}
	
	public function get_robots(){
		if(empty($this->url_data)) return false;
		
		$robots_url = 'http://' . $this->url_data['domain'] . '/robots.txt';
		
		if(!$this->check_url($robots_url)){
			return false;
		}
		
		$robots_text = @file($robots_url);
		
		if(empty($robots_text)){
			$this->robots_rules = false;
			return;
		}
		
		$user_agents = implode("|", array(preg_quote('*'),preg_quote(self::USER_AGENT)));
		
		$this->robots_rules = array();
		
		foreach($robots_text as $line){
			if(!$line = trim($line)) continue;
			
			if(preg_match('/^\s*User-agent: (.*)/i', $line, $match)) {
				$ruleApplies = preg_match("/($user_agents)/i", $match[1]);
			}
			if(!empty($ruleApplies) && preg_match('/^\s*Disallow:(.*)/i', $line, $regs)) {
				// an empty rule implies full access - no further tests required
				if(!$regs[1]) return true;
				// add rules that apply to array for testing
				$this->robots_rules[] = preg_quote(trim($regs[1]), '/');
			}
		}
		
		return $this->robots_rules;
	}
	
	public function check_robots($url){
		if(empty($this->robots_rules)) return true;
		
		$parsed_url = parse_url($url);
		
		foreach($this->robots_rules as $robots_rule){
			if(preg_match("/^$robots_rule/", $parsed_url['path'])) return false;
		}
		
		return true;
	}

	private function clean_text($text){
		$preg_patterns = array(
			"/[\x80-\xFF]/", //remove special characters
			"/&nbsp/",
			"/\s+/", //remove extra whitespace
		);
		$text = strip_tags(preg_replace($preg_patterns, " ", html_entity_decode($text, ENT_QUOTES, 'UTF-8')));
		
		return $text;
	}

	public function get_html(){
		if(!empty($this->dom)){
			return $this->dom->save();
		}
		else{
			return false;
		}
	}
	
	public function get_text($limit = null){
		if(!empty($this->text)) return $this->text;
		
		if(!is_null($limit) && is_numeric($limit)){
			return substr($this->clean_text($this->dom->plaintext), 0, $limit);
		}
		else{
			return $this->clean_text($this->dom->plaintext);
		}
	}

    public function get_title(){
    	if(!$page_title = $this->dom->find('head title', 0)){
    		return false;
    	}

    	return $this->clean_text($page_title->innertext);
    }

    public function get_description(){
    	if(!$page_description = $this->dom->find('head meta[name=description]', 0)){
    		return false;
    	}

    	return $this->clean_text($page_description->content);
    }

    public function get_keywords(){
    	if(!$page_keywords = $this->dom->find('head meta[name=keywords]', 0)){
    		return false;
    	}

    	return $this->clean_text($page_keywords->content);

    }

	public function get_links($exclude_terms, $include_terms){
		if(!empty($this->links)) return $this->links;
		
		$this->links = array();
		$anchor_tags = $this->dom->find('a[href]');
		
		foreach($anchor_tags as $anchor){
			$anchor_url = parse_url($anchor->href);
			if($anchor_url === false) continue;

			$anchor_href = '';
			if(empty($anchor_url['host'])){
				if(empty($anchor_url['path'])) continue;
				$anchor_href = $this->url_data['scheme'] . '://' . $this->url_data['host'] . ((!empty($anchor_url['path']) && substr($anchor_url['path'], 0, 1) != '/') ? '/' : '') . $anchor_url['path'];
			}
			else{
				$anchor_domain = implode(".", array_slice(explode(".", $anchor_url['host']), -2));
				if($anchor_domain != $this->url_data['domain']) continue;

				$anchor_href .= ((!empty($anchor_url['scheme'])) ? $anchor_url['scheme'] : 'http') . '://' . $anchor_url['host'] . ((!empty($anchor_url['path']) && substr($anchor_url['path'], 0, 1) != '/') ? '/' : '') . ((!empty($anchor_url['path'])) ? $anchor_url['path'] : '');
			}

			if($anchor_href == $this->url || array_key_exists($anchor_href, $this->links)) continue;

			//TODO
			//Add support for relative links (ex. A link on http://passpack.com/en/home/ with an href of ../about_us should be http://passpack.com/en/about_us
			//does plaintext content exist?

			if(!empty($exclude_terms) && is_array($exclude_terms)){
				$exclude_term_found = false;
				foreach($exclude_terms as $term){
					if(stripos($this->clean_text($anchor->innertext), $term) !== false && strlen($this->clean_text($anchor->innertext)) < 50){
						$exclude_term_found = true;
					}
					if(!empty($anchor_url['path'])){
						$path_segments = explode("/", $anchor_url['path']);
						$last_path_segment = array_pop($path_segments);
						if(stripos($last_path_segment, $term) !== false && strlen($last_path_segment) < 50){
							$exclude_term_found = true;
						}
					}
					
				}
				if($exclude_term_found) continue;
			}
			
			if(!empty($anchor_url['path']) && $this->check_robots($anchor_url['path']) !== true){
				continue;
			}
			
			if(!empty($include_terms) && is_array($include_terms)){ 
				$include_term_found = false;
				foreach($include_terms as $term){
					if(stripos($this->clean_text($anchor->innertext), $term) !== false && strlen($this->clean_text($anchor->innertext)) < 50){
						$include_term_found = true;
						continue;
					}
					if(!empty($anchor_url['path'])){
						$path_segments = explode("/", $anchor_url['path']);
						$last_path_segment = str_replace(array('-','_'), ' ', array_pop($path_segments));
						if(stripos($last_path_segment, $term) !== false && strlen($last_path_segment) < 50){
							$include_term_found = true;
							continue;
						}
					}
				}
			}
			
			if($include_term_found){
				$this->links[$anchor_href] = array(
					'raw_href' => $anchor->href,
					'full_href' => $anchor_href,
					'text' => $this->clean_text($anchor->innertext),
					//'path' => $anchor_url
				);
			}
		   
		}

		return $this->links;
	}

}