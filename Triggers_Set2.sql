delimiter //
drop trigger if exists Del_Learns_In_Student;
create trigger Del_Learns_In_Student
after delete on Student
for each row
begin
    delete from Learns_In where Learns_In.StudentID = old.StudentID;
end
//
delimiter ;

delimiter //
drop trigger if exists Del_Learns_In_Class;
create trigger Del_Learns_In_Class
after delete on ClassInSchool
for each row
begin
    delete from Learns_In where Learns_In.ClassID = old.ClassID;
end
//
delimiter ;

delimiter //
drop trigger if exists Del_Class_Teacher;
create trigger Del_Class_Teacher
after delete on Teacher
for each row
begin
    delete from ClassInSchool where ClassInSchool.TeacherID = old.TeacherID;
end
//
delimiter ;
