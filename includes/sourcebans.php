<?php
class SourceBans{
    public $db;

    public function __construct(){
        $this->db = mysqli_connect(SB_HOST,SB_USER,SB_PASS,SOURCEBANS_DB)or die($this->db->error . " " . $this->db->errno);
    }


    public function __destruct(){
       mysqli_close($this->db);
    }


    public function serverList(){
        $i=0;
        if($result = $this->db->query("SELECT `ip` , `port` FROM ". SB_PREFIX ."_servers")){

            while($server[$i] = $result->fetch_array(MYSQLI_ASSOC)){
                $i++;
            }

            return $server;

        }else{
            return FALSE;
        }
    }

}
?>
