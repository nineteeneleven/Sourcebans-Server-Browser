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


    public function queryServers($query){
        $result = $this->db->query("SELECT * FROM sb_servers");
            while($server = $result->fetch_array(MYSQLI_ASSOC)){
                $srcds_rcon = new srcds_rcon();
                $OUTPUT = $srcds_rcon->rcon_command($server['ip'], $server['port'], $server['rcon'], $query);
            }
            return true;
    }


    public function queryServersResponse($query){
        $fail=0;
        $success=0;
        $result = $this->db->query("SELECT * FROM sb_servers");
            while($server = $result->fetch_array(MYSQLI_ASSOC)){

                $srcds_rcon = new srcds_rcon();

                $OUTPUT = $srcds_rcon->rcon_command($server['ip'], $server['port'], $server['rcon'], $query);
                
             if (!$OUTPUT){

                $OUTPUT = "Unable to connect!";

                $fail = $fail + 1;

            }else{

                $success = $success + 1;    

            }

            echo "<div class='confirmSent'><hr />" . $server['ip'] . ":" .$server['port'] . " respoonse <br />" . "<textarea rows='10' cols='110'>$OUTPUT</textarea>" . "</div><br />";
     
            }
         if($fail===0){
            print("<script type='text/javascript'>  alert('{$success} Game Servers Successfully Queried.');</script>");
        }else{
            print("<script type='text/javascript'>  alert('{$success} Game Servers Successfully Queried. \\n {$fail} servers were unable to connect');</script>");

        }
    }



private function getGroup($tier){
    global $group1, $group2;
        if (TIERED_DONOR) {
           if ($tier=="1") {
                $group['name'] = $group1['name'];
                $group['group_id'] = $group1['group_id'];
                $group['srv_group_id'] = $group1['srv_group_id'];
                $group['server_id'] = $group1['server_id'];
           }else{
                $group['name'] = $group2['name'];
                $group['group_id'] = $group2['group_id'];
                $group['srv_group_id'] = $group2['srv_group_id'];
                $group['server_id'] = $group2['server_id'];
           }
        }else{
            $group['name'] = $group1['name'];
            $group['group_id'] = $group1['group_id'];
            $group['srv_group_id'] = $group1['srv_group_id'];
            $group['server_id'] = $group1['server_id'];
        }
        return $group;
}



   public function addDonor($steam_id, $username, $tier){
//check sourcebans database to see if user is already in there
    $group = $this->getGroup($tier);

    $sb_pw = "1fcc1a43dfb4a474abb925f54e65f426e932b59e";

    $result= $this->db->query("SELECT * FROM ".SB_PREFIX."_admins WHERE authid='".$steam_id."';") or die($this->db->error . " " . $this->db->errno);

        if($result){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $sb_aid = $row['aid'];
        } 

    if (isset($sb_aid)) {
        return ("user is already in the Sourcebans database.<br /> Aborting. <br /> <a href='javascript:history.go(-1);'>Click here to go back</a>");
    } else {
        //if not, PUT EM IN!

                $sb_sql = "INSERT INTO `".SOURCEBANS_DB."` . `".SB_PREFIX."_admins` (user,authid,password,gid,extraflags,immunity,srv_group) VALUES ('{$username}', '{$steam_id}', '{$sb_pw}' , '-1' , '0' , '0', '".$group['name']."');";
                $this->db->query($sb_sql) or die($this->db->error . " " . $this->db->errno);
                
                $admin_id = $this->db->insert_id;
                
                $sb_sql2 = "INSERT INTO `".SOURCEBANS_DB."` . `".SB_PREFIX."_admins_servers_groups` (admin_id,group_id,srv_group_id,server_id) VALUES('{$admin_id}', '".$group['group_id']."', '".$group['srv_group_id']."', '".$group['server_id']."');"; 
                $this->db->query($sb_sql2) or die($this->db->error . " " . $this->db->errno);
                
                // if (!$this->queryServers('sm_reloadadmins')) { 
                //     return "<h1>Server rehash failed</h1>";
                // }
        }

        return TRUE;

    }


    public function removeDonor($steam_id,$tier){
        global $group1, $group2;    
        $group = $this->getGroup($tier);
        $result= $this->db->query("SELECT * FROM `".SOURCEBANS_DB."` . `".SB_PREFIX."_admins` WHERE authid='".$steam_id."';") or die($this->db->error . " " . $this->db->errno);
        if($result){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $admin_id = $row['aid'];
            $admin_group = $row['srv_group'];
            }else{die();}

        if ($admin_group == $group['srv_group'] || $admin_group == $group1['name'] || $admin_group == $group2['name']){
            $sb_sql = "DELETE FROM `".SOURCEBANS_DB."`.`".SB_PREFIX."_admins` WHERE authid ='" . $steam_id ."';";
            $this->db->query($sb_sql) or die("<h1 class='error'>Failed deleting from admins table" .$this->db->error . " " . $this->db->errno."</h1>");

            $sb_sql2 = "DELETE FROM `".SOURCEBANS_DB."`.`".SB_PREFIX."_admins_servers_groups` WHERE admin_id ='" . $admin_id ."';";
            $this->db->query($sb_sql2) or die("<h1 class='error'>Failed deleting from admins_servers_groups table" .$this->db->error . " " . $this->db->errno."</h1>");

            // if (!$this->queryServers('sm_reloadadmins')) {
            //     return "<h1 class='error'>Server rehash failed</h1>";
            // }
        }else{
            die ("<h1 class='error'> user is in a different sourcebans group.<br />Aborting.<hr /><a href='javascript:history.go(-1);'>Click here to go back</a></h1>");
        }
        return TRUE;

        break;
    }

}
?>