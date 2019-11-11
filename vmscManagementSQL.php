<?php
	class ManagementSQL{
		static public function report_age(){
			return 'select sum(case when left(ltrim(rtrim(P.IDNo)),1)=0 then 1 else 0 end) as One,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=1 then 1 else 0 end) as Two,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=2 then 1 else 0 end) as Three,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=3 then 1 else 0 end) as Four,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=4 then 1 else 0 end) as Five,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=5 then 1 else 0 end) as Six,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=6 then 1 else 0 end) as Seven,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=7 then 1 else 0 end) as Eight,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=8 then 1 else 0 end) as Nine,
						   sum(case when left(ltrim(rtrim(P.IDNo)),1)=9 then 1 else 0 end) as Ten
					  from perssonel P
					  join (SELECT distinct PEE.PersonId,X.Edate 
							  FROM `personentryexit` PEE
							  join (select PersonId,max(EntryDate) as EDate
									 from personentryexit
									where Date(EntryDate)>=Date(?)
									  and Date(EntryDate)<Date(?)
									group by PersonId) X
								 on PEE.EntryDate=X.EDate
								and PEE.PersonId=X.PersonId) Z
					    on P.PersonId=Z.PersonId
					 where char_length(ltrim(rtrim(P.IDNo)))>=7
					   and case when 0=? then 1=1 else P.Complete=? end';
		}
		static public function report_addictions($druglist,$abbr){
			$query='select ';
		//	echo(implode(",",$druglist)."<br>".sizeof($druglist)."<br>");
		//	echo(implode(",",$abbr)."<br>".sizeof($abbr)."<br>");
			for($x=0;$x<sizeof($druglist);$x++){
				$query.='sum(case when PA.AddictionId='.$druglist[$x].' then 1 else 0 end) as '.$abbr[$x].' ';
				if(sizeof($druglist)!=$x+1){
					$query.=',';
				}
			}
			$query.=' from personaddictions PA
					  left join (select PersonId,max(EntryDate) AS EDate
								   from personentryexit
								  where Date(EntryDate) between Date(?) and Date(?)
								  group by PersonId) X
						on PA.PersonId=X.PersonId 
					 where AddictionId in (?)';
			return $query;
		}
	}
	
?>