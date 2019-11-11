<?php
class SysUserManage{
    static public function get_user(){
        return 'select U.UserId,Name,Surname,Email,Password,SU.SecId
				  from users U
				  join secusers SU
					on U.UserId=SU.UserId
				 where U.UserId=?';
    }
    static public function security(){
        return 'select SecId, Security  
                  from security 
                 where SecId>? 
                 order by SecId ASC';
    }
    static public function add_user(){
        return 'insert into users(Name,Surname,Email,Password,Inactive)values(?,?,?,?,0)';
    }
    static public function get_userid(){
        return 'select UserId
			      from users
			     where Email=?
			       and Password=?';
    }
    static public function add_secusers(){
        return 'insert into secusers(SecId,UserId)values(?,?)';
    }
    static public function get_user_full(){
        return 'select Name,Surname,Email,Password,Security
    			  from users U
    			  join secusers SU
    			    on U.UserId=SU.UserId
    			  join security S
    			    on SU.SecId=S.SecId
    			 where U.UserId=?';
    }
    static public function get_logged_in(){
        return 'select UL.LoggedIn,UL.LoggedOut,U.Name,U.Surname
    			  from userlogin UL
    			  join users U
    			    on UL.Userid=U.UserId
    		     where ( UL.LoggedOut is null or UL.LoggedOut="0000-00-00 00:00:00" )
    			   and Activeid>?
    			 order by ActiveId DESC';
    }
    static public function delete_sec_user(){
        return 'delete
    			  from secusers
    			 where UserId=?';
    }
    static public function delete_user(){
        return 'update users
				   set Inactive=1
    			 where UserId=?';
    }
    static public function get_user_part(){
        return 'select U.UserId,Name,Surname,Email,Security
				  from users U
				  join secusers SU
				    on U.UserId=SU.UserId
				  join security S
				    on SU.SecId=S.SecId
				 where U.UserId>? 
				   and Inactive=0';
    }
    static public function update_user(){
        return 'update users 
                   set Name=?,
                       Surname=?,
                       Email=?,
                       Password=?
                 where UserId=?';
    }
    static public function update_sec_user(){
        return 'update secusers     
                   set SecId=?
                 where UserId=?';
    }
    
    
    
    
    
    
    
    
    
    
    
    
}
?>