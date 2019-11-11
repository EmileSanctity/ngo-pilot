	<?php

class PersonProfileSql{
    static public function get_info_profile(){
        $query='select P.Name, P.Surname, P.IDNo, P.Addictions,
					   CS.Name as Completed,
					   CS.CompleteId as CompletedId,
					   case when char_length(P.IDNo)>=7 then floor(datediff(date(now()),date(concat(case when substring(ltrim(P.IDNo),1,1)<=2 then concat("20","",substring(ltrim(P.IDNo),1,2)) else concat("19","",substring(ltrim(P.IDNo),1,2)) end,"-",
       substring(ltrim(P.IDNo),3,2),"-",substring(ltrim(P.IDNo),5,2))))/365.25) else "Undetermined" end as Age,
					   case when P.Drivers=0 then "Yes"
					        when P.Drivers=1 then "No"
							else "Not Recorded" end as Drivers,
					   case when P.Sassa=0 then "Yes"
					        when P.Sassa=1 then "No"
							else "Not Recorded" end as Sassa,
					   case when count(PA.AddictionId)>=1 then "Yes" else "No" end as Addict,
					   case when count(PA.AddictionId)>=1 then 2 else 1 end as AddictInt,
					   case when char_length(ltrim(P.IDNo))>=7 then
							   case when substr(P.IDNo,7,1)>4 then "Male" else "Female" end
									else "No data captured" end as Gender,
					   concat("include/personimages/",I.ImageId,".",I.Ext) as Image,
					   case when isnull(D.SickDays)=1 then 0 else D.SickDays end as SickDays,
					   Y.AddictionList,Z.AddictionIdList,
					   case when isnull(max(PS.SpouseId))=1 then "No" else "Yes" end as Spouse,
					   count(distinct PN.NodeId) as Dependants
				  from perssonel P
				  left join personaddictions PA
					on P.PersonId=PA.PersonId
				  left join personspouse PS
					on P.PersonId=PS.PersonId
				  left join personnodes PN
					on P.PersonId=PN.PersonId
				  left join (select max(ImageId) as ImageId, Ext, PersonId from personimages group by PersonId) I
					on P.PersonId=I.PersonId
				  left join (select sum(datediff(FinishDate,StartDate)+1) as SickDays,PersonId
							   from personsickleave
							  group by PersonId) D
					on P.PersonId=D.PersonId
				  left join (select PA.PersonId,group_concat(A.Abbr) as AddictionList
							   from personaddictions PA
							   join addictions A
								 on PA.AddictionId=A.AddictionId
							  group by PersonId) Y
					on P.PersonId=Y.PersonId
				  left join (select PersonId,group_concat(AddictionId) as AddictionIdList
							   from personaddictions
							  group by PersonId) Z
					on P.PersonId=Z.PersonId
				  left join completestatus CS
				    on P.Complete=CS.CompleteId
				 where P.PersonId=?';
        return $query;
    }
    static public function get_entry_exit(){
        $query='select PEE.DateId,
					   date(PEE.EntryDate) as EntryDate,
					   date(PEE.ExitDate) as ExitDate,
					   PEE.Note,
					   X.TotalSum,
					   case when ifnull(null,DateDiff(Date(PEE.ExitDate),Date(PEE.EntryDate))+1) is null
					   then 0
					   else DateDiff(Date(PEE.ExitDate),Date(PEE.EntryDate))+1 end as Total
				  from personentryexit PEE
				  left join (select PersonId,sum(case when ifnull(null,DateDiff(Date(ExitDate),Date(EntryDate))+1) is null
													  then 0
													  else DateDiff(Date(ExitDate),Date(EntryDate))+1 end) as TotalSum
							   from personentryexit
							  where PersonId=?
							  group by PersonId) X
					on PEE.PersonId=X.PersonId
				 where PEE.PersonId=?
				 order by Date(PEE.EntryDate) DESC';
        return $query;
    }
    static public function get_leave(){
        $query='select PL.LeaveId,
					   Date(PL.StartDate) as StartDate,
					   Date(PL.FinishDate) as FinishDate,
					   PL.Comment,
					   X.TotalSum,
					   case when ifnull(null,DateDiff(Date(FinishDate),Date(StartDate))+1) is null
													  then 0
													  else DateDiff(Date(FinishDate),Date(StartDate))+1 end as Total
				  from personleave PL
				  left join (select PersonId,sum(case when ifnull(null,DateDiff(Date(FinishDate),Date(Startdate))+1) is null
													  then 0
													  else DateDiff(Date(FinishDate),Date(Startdate))+1 end) as TotalSum
							   from personleave
							  where PersonId=?
							  group by PersonId) X
					on PL.PersonId=X.PersonId
				 where PL.PersonId=?
				 order by Date(PL.Startdate) DESC';
        return $query;
    }
    static public function get_sick_leave(){
        $query='select PSL.SickId,
					   Date(PSL.StartDate) as StartDate,
					   Date(PSL.FinishDate) as FinishDate,
					   PSL.Comment,
					   X.Total,
					   case when ifnull(null,DateDiff(Date(PSL.FinishDate),Date(PSL.Startdate))+1) is null then 0
							else DateDiff(Date(PSL.FinishDate),Date(PSL.Startdate))+1 end as Days,
					   case when DR.NoteId=0 then "No attachment"
							when DR.NoteId is null then "No attachment"
							else concat(DR.Name,".",DR.Ext) end as NoteName,DR.NoteId,DR.Ext
				  from personsickleave PSL
				  left join (select max(NoteId) as NoteId, Name, SickId,Ext
							   from doctorsnotes
							  group by SickId) DR
					on PSL.SickId=DR.SickId
				  left join (select PersonId,sum(case when ifnull(null,DateDiff(Date(FinishDate),Date(Startdate))+1) is null
													  then 0
													  else DateDiff(Date(FinishDate),Date(Startdate))+1 end) as Total
							   from personsickleave
							  where PersonId=?
							  group by PersonId) X
					on PSL.PersonId=X.PersonId
				 where PSL.PersonId=?
				 order by PSL.StartDate DESC';
        return $query;
    }
    static public function get_spouse(){
        $query='select SpouseId, Name, Surname, IDNo,
					   case when char_length(IDNo)>=7 then floor(datediff(date(now()),date(concat(case when substring(ltrim(IDNo),1,1)<=2 then concat("20","",substring(ltrim(IDNo),1,2)) else concat("19","",substring(ltrim(IDNo),1,2)) end,"-",
       substring(ltrim(IDNo),3,2),"-",substring(ltrim(IDNo),5,2))))/365.25) else "Undetermined" end as Age,OnSystem
			  from personspouse
			 where PersonId=?';
        return $query;
    }
    static public function get_nodes(){
        $query='select NodeId,Name,Date(BirthDate) as BirthDate
			      from personnodes
			     where PersonId=?';
        return $query;
    }
    static public function get_education(){
        $query='select QualId,Qualification
			      from personqualifications
			     where PersonId=?';
        return $query;
    }
    static public function get_employment(){
        $query='select EmployId,Employer,Name,CellNo
			      from personemployers
			     where PersonId=?';
        return $query;
    }
    static public function add_entry_exit(){
        return 'insert into personentryexit(EntryDate, ExitDate, Note, PersonId)
					   values(?,?,?,?)';
    }
    static public function update_entry_exit(){
        return 'update personentryexit
				   set EntryDate=?,
					   ExitDate=?,
					   Note=?
				 where DateId=?
				   and PersonId=?';
    }
    static public function delete_entry_exit(){
        return 'delete from personentryexit where DateId=?';
    }
    static public function get_profile_image(){
        return 'select max(ImageId) as ImageId,Ext
                  from personimages
                 where PersonId=?';
    }
    static public function update_profile_info(){
        return 'update perssonel
    			   set Name=ltrim(rtrim(?)),
    				   Surname=ltrim(rtrim(?)),
    				   IDNo=ltrim(rtrim(?)),
    				   Addictions=?,
					   Drivers=?,
					   Sassa=?,
					   Complete=?
    			 where PersonId=?';
    }
    static public function verify_disciplinary(){
		return "select count(SecId) as Cnt
				  from secusers
				 where UserId=?
				   and SecId=?";
	}
    static public function get_disciplinary(){
		return 'select PD.DiscId, PD.PersonId, PD.OffenceId, O.Offence,
					   Date(PD.OffenceDate) as OffenceDate,
					   Date(PD.CaptureDate) as CaptureDate,
					   PD.Discipline, PD.Restart,
					   case when D.DocId=0 then "No attachment"
							when D.DocId is null then "No attachment"
							else concat(D.Name,".",D.Ext) end as NoteName,
					   D.DocId,D.Ext,D.Name
				  from persondisciplinary PD
				  left join (select max(DocId) as DocId,Name,Ext,DiscId
							   from persondocs
							  group by DiscId)D
					on PD.DiscId=D.DiscId
				  left join offences O
				    on PD.OffenceId=O.OffenceId
				 where PD.PersonId=?
				 order by PD.OffenceDate, PD.CaptureDate DESC';
	}
    static public function get_offences(){
		return 'select OffenceId as Id,Offence as Of
				  from offences
				 where OffenceId > ?';
	}
    static public function get_department(){
		return 'select PD.PersonDeptId,PD.DeptId,Date(PD.StartDate) as StartDate,Date(PD.FinishDate) as FinishDate,D.Name
				  from persondepartment PD
				  left join department D
					on PD.DeptId=D.DeptId
				 where PD.PersonId=?
				 order by PD.Startdate DESC';
	}
    static public function get_counsellor_notes(){
		return 'select C.CounsId,C.PersonId,C.UserId,C.Title,C.CaptureDate,C.Note,U.Name,U.Surname
				  from counsellor C
				  left join users U
				    on C.Userid=U.UserId
				 where C.PersonId=?
				 order by CaptureDate DESC';
	}
	static public function add_counsellor_note(){
		return 'insert into counsellor(PersonId, UserId,Title,Note,CaptureDate)values(?,?,?,?,now())';
	}
	static public function get_person_intake_docs(){
		return 'select P.IntakeId, concat(P.Name, ".",P.Ext) as NoteName, Date(P.UploadedOn) as UploadedOn, P.Ext, P.Name
				  from personintakedocs P
				 where P.PersonId=?
                 order by P.UploadedOn DESC';
	}
	static public function get_person_intake_docs_count(){
        return 'select count(*) as Count from personintakedocs where PersonId=?';
    }
	static public function get_medical(){
		return 'select MedId, PersonId, HealthStatus, Conditions, UseMeds, Medications 
				  from personmedical 
				 where PersonId=?';
	}







}

?>
