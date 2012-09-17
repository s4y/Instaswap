<?
class InstaswapDB {
	private $connection;
	private function get_connection(){
		if (!$this->connection) {
			$this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
		}
		return $this->connection;
	}
	private function user_id_by_key($key, $val){
		$query = $this->get_connection()->prepare('SELECT `id` FROM users WHERE `' . $key . '` = ? LIMIT 1');
		if ($query->execute(array($val))) {
			if ($results = $query->fetch(PDO::FETCH_ASSOC)) {
				return $results['id'];
			}
		}
	}
	function user_id_by_token($token){
		return $this->user_id_by_key('oauth_token', $token);
	}
	function user_id_by_instapaper_id($id){
		return $this->user_id_by_key('instapaper_user', $id);
	}
	function user_id_by_name($name){
		return $this->user_id_by_key('name', $name);
	}
	function create_user($name, $instapaper_id, $token, $token_secret){
		$db = $this->get_connection();
		$query = $db->prepare('INSERT INTO users (`name`, `instapaper_user`, `oauth_token`, `oauth_token_secret`) VALUES (?, ?, ?, ?)');
		if($query->execute(array($name, $instapaper_id, $token, $token_secret))){
			if($results = $db->query('SELECT LAST_INSERT_ID()')) {
				$fuck_php = $results->fetch(PDO::FETCH_NUM);
				return $fuck_php[0];
			}
		}
	}
	function update_user_token($id, $token, $token_secret){
		$query = $this->get_connection()->prepare('UPDATE users SET `oauth_token`=?, `oauth_token_secret`=? WHERE `id`=?');
		return $query->execute(array($token, $token_secret, $id));
	}
	function get_user_by_id($id){
		$query = $this->get_connection()->prepare('SELECT * FROM users WHERE `id` = ? LIMIT 1');
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	function get_user_by_name($name){
		if (($user_id = $this->user_id_by_name($name))) {
			return $this->get_user_by_id($user_id);
		}
	}
}
?>
