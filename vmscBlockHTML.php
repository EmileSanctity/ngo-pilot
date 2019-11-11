<?php
	require_once('vmscFunctions.php');
class BlockHTML{
    public static function tabs_layout_table($tabs,$titles){
        $x=0;
        $tabs='<table class="tabs" style="border:0px;"><tr>';
        foreach($titles as $key => $title){
            $tabs.='<td class="tab">
        				<button class="tabbutton" id="tabbutton'.($key+1).'" onclick="tabstyle(this.id,\'tr'.($x).'\',\'tr'.($x+1).'\');checkajax(this.id);">
        					'.$title.'
        			</button>
        			</td>';
            $x+=2;
        }
        $tabs.='</tr></table>';
        
        echo($tabs);      
    }
    public static function content_layout_table($userid,$rows,$edit=''){
		$query='select SecId from secusers where UserId=?';
		$types="i";
		$params=array($userid);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			$secid=$row['SecId'];
		}mysqli_free_result($result);
        $content='<div class="contentwrapper" >
		              <div class="content">
			             <button onclick="refresh();" style="float:right;border-radius:50%;" ><img src="include/icons/view_refresh.png" width="30px" height="30px" /></button>';

        if ($edit>'' && $secid != 3){
            $content.='  <button id="editprofile" onclick="ajax(\''.$edit.'\');" style="float:right;margin:7px 10px;border-radius:20%;" >Edit</button>';
        }
        $content.='   </div>
		          <div id="wrapper">';
        
        $x=0;
        for ($i = 0; $i < $rows; $i++) {
            $content.='     <div id="tr'.($x).'" class="content"><div class="content" id="ajaxresponse'.($x).'"></div></div>
                            <div id="tr'.($x+1).'" class="content"><div class="content" id="ajaxresponse'.($x+1).'"></div></div>';
            
            $x+=2;
        }
        
        $content.='   </div>
	               </div>';
        
        echo($content);
    }
}
?>