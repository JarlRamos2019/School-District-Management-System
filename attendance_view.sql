drop view if exists Enrollment;
create view Enrollment as 
select concat(Student.FirstName, ' ', Student.LastName) as Student, 
ClassID as Class, 
concat(Teacher.FirstName, ' ', Teacher.LastName) as Teacher,
Student.SchoolName as School
from Learns_In natural join Student 
natural join ClassInSchool 
join Teacher on ClassInSchool.TeacherID = Teacher.TeacherID
order by ClassID, Teacher;

drop view if exists StudentPerformance;
create view StudentPerformance as
select concat(Student.FirstName, ' ', Student.LastName) as Student, SchoolName,
GPA
from Student;

drop view if exists DeansListSeniors;
create view DeansListSeniors as
select concat(Student.FirstName, ' ', Student.LastName) as Student, SchoolName, GPA
from Student
where GPA > 3.5
and Grade = 12
order by SchoolName, GPA desc;

drop view if exists DeansList;
create view DeansList as
select concat(Student.FirstName, ' ', Student.LastName) as Student, SchoolName, GPA
from Student
where GPA > 3.5
order by SchoolName, GPA desc;


drop view if exists MarchingBandStudents;
create view MarchingBandStudents as
select concat(Student.FirstName, ' ', Student.LastName) as Student, SchoolName
from Student natural join Extracurriculars
where ExtraName = "Marching Band"
order by SchoolName;

drop view if exists TotalDetentionsInSchool;

create view TotalDetentionsInSchool as
select SchoolName, ClassID as Class, sum(Num_Detentions) as DetentionNumber
from School natural join Student natural join Learns_In
group by SchoolName, ClassID;

