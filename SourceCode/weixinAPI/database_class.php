<?php
Class DBClass {

	private $link_id;
	private $handle;
	private $is_log;
	private $time;

	//���캯��
	public function __construct() {
		$this->time = $this->microtime_float();
		require_once("config.db.php");
		$this->connect($db_config["hostname"], $db_config["username"], $db_config["password"], $db_config["database"], $db_config["pconnect"]);
		$this->is_log = $db_config["log"];
		if($this->is_log){
			$handle = fopen($db_config["logfilepath"]."dblog.txt", "a+");
			$this->handle=$handle;
		}
	}
	
	//���ݿ�����
	private function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0,$charset='utf8') {
		if( $pconnect==0 ) {
			$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
			if(!$this->link_id){
				$this->halt("���ݿ�����ʧ��");
			}
		} else {
			$this->link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw);
			if(!$this->link_id){
				$this->halt("���ݿ�־�����ʧ��");
			}
		}
		if(!@mysql_select_db($dbname,$this->link_id)) {
			$this->halt('���ݿ�ѡ��ʧ��');
		}
		@mysql_query("set names ".$charset);
	}
	
	//��ѯ 
	public function query($sql) {
		$this->write_log("��ѯ ".$sql);
		$query = mysql_query($sql,$this->link_id);
		if(!$query) $this->halt('Query Error: ' . $sql);
		return $query;
	}
	
	//��ȡһ����¼��MYSQL_ASSOC��MYSQL_NUM��MYSQL_BOTH��				
	public function get_one($sql,$result_type = MYSQL_ASSOC) {
		$query = $this->query($sql);
		$rt =& mysql_fetch_array($query,$result_type);
		$this->write_log("��ȡһ����¼ ".$sql);
		return $rt;
	}

	//��ȡȫ����¼
	public function get_all($sql,$result_type = MYSQL_ASSOC) {
		$query = $this->query($sql);
		$i = 0;
		$rt = array();
		while($row =& mysql_fetch_array($query,$result_type)) {
			$rt[$i]=$row;
			$i++;
		}
		$this->write_log("��ȡȫ����¼ ".$sql);
		return $rt;
	}
	
	//����
	public function insert($table,$dataArray) {
		$field = "";
		$value = "";
		if( !is_array($dataArray) || count($dataArray)<=0) {
			$this->halt('û��Ҫ���������');
			return false;
		}
		while(list($key,$val)=each($dataArray)) {
			$field .="$key,";
			$value .="'$val',";
		}
		$field = substr( $field,0,-1);
		$value = substr( $value,0,-1);
		$sql = "insert into $table($field) values($value)";
		$this->write_log("���� ".$sql);
		if(!$this->query($sql)) return false;
		return true;
	}

	//����
	public function update( $table,$dataArray,$condition="") {
		if( !is_array($dataArray) || count($dataArray)<=0) {
			$this->halt('û��Ҫ���µ�����');
			return false;
		}
		$value = "";
		while( list($key,$val) = each($dataArray))
		$value .= "$key = '$val',";
		$value .= substr( $value,0,-1);
		$sql = "update $table set $value where 1=1 and $condition";
		$this->write_log("���� ".$sql);
		if(!$this->query($sql)) return false;
		return true;
	}

	//ɾ��
	public function delete( $table,$condition="") {
		if( empty($condition) ) {
			$this->halt('û������ɾ��������');
			return false;
		}
		$sql = "delete from $table where 1=1 and $condition";
		$this->write_log("ɾ�� ".$sql);
		if(!$this->query($sql)) return false;
		return true;
	}

	//���ؽ����
	public function fetch_array($query, $result_type = MYSQL_ASSOC){
		$this->write_log("���ؽ����");
		return mysql_fetch_array($query, $result_type);
	}

	//��ȡ��¼����
	public function num_rows($results) {
		if(!is_bool($results)) {
			$num = mysql_num_rows($results);
			$this->write_log("��ȡ�ļ�¼����Ϊ".$num);
			return $num;
		} else {
			return 0;
		}
	}

	//�ͷŽ����
	public function free_result() {
		$void = func_get_args();
		foreach($void as $query) {
			if(is_resource($query) && get_resource_type($query) === 'mysql result') {
				return mysql_free_result($query);
			}
		}
		$this->write_log("�ͷŽ����");
	}

	//��ȡ�������id
	public function insert_id() {
		$id = mysql_insert_id($this->link_id);
		$this->write_log("�������idΪ".$id);
		return $id;
	}

	//�ر����ݿ�����
	protected function close() {
		$this->write_log("�ѹر����ݿ�����");
		return @mysql_close($this->link_id);
	}

	//������ʾ
	private function halt($msg='') {
		$msg .= "\r\n".mysql_error();
		$this->write_log($msg);
		die($msg);
	}

	//��������
	public function __destruct() {
		$this->free_result();
		$use_time = ($this-> microtime_float())-($this->time);
		$this->write_log("���������ѯ����,����ʱ��Ϊ".$use_time);
		if($this->is_log){
			fclose($this->handle);
		}
	}
	
	//д����־�ļ�
	private function write_log($msg=''){
		if($this->is_log){
			$text = date("Y-m-d H:i:s")." ".$msg."\r\n";
			fwrite($this->handle,$text);
		}
	}
	
	//��ȡ������
	private function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}

?>