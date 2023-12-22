delimiter //
drop procedure if exists Enroll;
create procedure Enroll(
    in sID int unsigned,
    in cID varchar(15),
    in perc float
)
begin
   
    insert into Learns_In (ClassID, StudentID, Percentage)
    values (cID, sID, perc);
 
end;
//
delimiter ;

delimiter //
drop procedure if exists AutoEnroll;
create procedure AutoEnroll(
    in sID int unsigned,
    in numOfClasses int,
    in perc float
)
begin
    set foreign_key_checks = 0;
    set @ctr = 0;
    the_loop: loop
        if @ctr >= numOfClasses then
            leave the_loop;
        end if;
        insert into Learns_In (ClassID, StudentID, Percentage)
        values (
            (select ClassID from ClassInSchool 
            order by rand() limit 1),
            sID, perc);
        set @ctr = @ctr + 1;
        iterate the_loop;
    end loop;
    set foreign_key_checks = 1;
end;
//
delimiter ;

delimiter //
drop procedure if exists GetSchoolAverage;
create procedure GetSchoolAverage(
    in sName varchar(50)
)
begin
    select avg(GPA) 
    from Student
    where SchoolName = sName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetDeansListSeniorsFromSchool;
create procedure GetDeansListSeniorsFromSchool(
    in sName varchar(50)
)
begin
    select Student, GPA
    from DeansListSeniors
    where SchoolName = sName;
end
//
delimiter ;
delimiter //
drop procedure if exists GetStudentCount;
create procedure GetStudentCount(
    in sName varchar(50)
)
begin
    select count(StudentID) 
    from Student
    where SchoolName = sName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetStudentClasses;
create procedure GetStudentClasses(
    in sID int unsigned
)
begin
    select distinct ClassID, Percentage
    from Student
    natural join Learns_In
    natural join ClassInSchool
    where StudentID = sID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetStudentClassesByName;
create procedure GetStudentClassesByName(
    in fName varchar(50),
    in lName varchar(50)
)
begin
    select ClassID, Teacher.FirstName, Teacher.LastName, Percentage
    from Student
    natural join Learns_In
    natural join ClassInSchool
    cross join Teacher 
    where ClassInSchool.TeacherID = Teacher.TeacherID
    and Student.FirstName = fName
    and Student.LastName = lName; 
end
//
delimiter ;

delimiter //
drop procedure if exists GetStudentCountInSchool;
create procedure GetStudentCountInSchool(
    in sName varchar(50)
    )
    begin
        select count(*)
        as StudentCount 
        from Student 
        group by SchoolName
        having SchoolName = sName;
    end
//
delimiter ;

delimiter //
drop procedure if exists GetAveragePercentageInSchool;
create procedure GetAveragePercentageInSchool (
    in sName varchar(50)
)
begin
    select avg(Percentage)
    as AveragePercentage
    from Learns_In
    natural join Student
    group by SchoolName
    having SchoolName = sName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetAverageGPAInSchool;
create procedure GetAverageGPAInSchool (
    in sName varchar(50)
)
begin
    select avg(GPA)
    as AverageGPA
    from Student
    group by SchoolName
    having SchoolName = sName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetClassStatistics;
create procedure GetClassStatistics (
    in sName varchar(50)
)
begin
    select ClassID, Teacher, isHonorsOrAP, round(avg(Percentage), 2) as AveragePercentage
    from ( 
        select ClassID, concat(FirstName, ' ', LastName) as Teacher, isHonorsOrAP, Percentage
        from ClassInSchool
        natural join Teacher
        natural join Learns_In
        where SchoolName = sName
    ) as ThatTable
    group by ClassID;
end
//
delimiter ;

delimiter //
drop procedure if exists ShowTeacherClasses;
create procedure ShowTeacherClasses (
    in tID int
)
begin
    select * 
    from ClassInSchool
    where TeacherID = tID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetSpecificClassInSchool;
create procedure GetSpecificClassInSchool (
    in sName varchar(50),
    in cID varchar(15)
)
begin
    select ClassInSchool.*, 
    round(avg(Percentage), 2) as AvgPercentage,
    count(*) as StudentCount,
    concat(FirstName , " " , LastName) as Teacher
    from ClassInSchool
    natural join Teacher
    natural join Learns_In
    where SchoolName = sName
    and ClassID = cID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetClassWithNoStudents;
create procedure GetClassWithNoStudents (
    in sName varchar(50),
    in cID varchar(15)
)
begin
    select ClassInSchool.*, concat(FirstName, " ", LastName) as Teacher
    from ClassInSchool
    natural join Teacher
    where SchoolName = sName
    and ClassID = cID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetClassStudents;
create procedure GetClassStudents (
    in cID varchar(15),
    in tID int
)
begin
    select concat(Student.FirstName, " ", Student.LastName) as Student,
    Percentage
    from ClassInSchool
    natural join Learns_In
    natural join Student
    where ClassInSchool.ClassID = cID
    and TeacherID = tID
    order by Student;
end
//
delimiter ;

delimiter //
drop procedure if exists HarvestDistrictStudentData;
create procedure HarvestDistrictStudentData (
    in dName varchar(50)
)
begin
    select count(StudentID) as StudentCount,
    round(avg(GPA), 2) as AverageGPA,
    round(avg(F_Hardship)) as AvgHardship,
    round(avg(Num_Suspensions), 1) as AvgSuspensions,
    round(avg(Num_Detentions), 1) as AvgDetentions,
    round(avg(Num_Absences), 1) as AvgAbsences
    from District
    cross join School on District.Name = School.DistrictName
    cross join Student on School.Name = Student.SchoolName
    where District.Name = dName;
end
//
delimiter ;

delimiter //
drop procedure if exists HarvestDistrictTeacherData;
create procedure HarvestDistrictTeacherData (
    in dName varchar(50)
)
begin
    select count(TeacherID) as TeacherCount,
    round(avg(SatisfactionLevel)) as AvgSatisfaction,
    round(avg(Pay)) as AvgPay
    from District
    cross join School on District.Name = School.DistrictName
    cross join Teacher on School.Name = Teacher.SchoolName
    where District.Name = dName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetStudentFromClasses;
create procedure GetStudentFromClasses (
    in sID int,
    in tID int
)
begin
    select StudentID, Student.FirstName as FirstName,
    Student.MidInitial as MidInitial, 
    Student.LastName as LastName,
    Percentage, Num_Absences, ClassID
    from Student
    natural join Learns_In
    natural join ClassInSchool
    where TeacherID = tID
    and Student.StudentID = sID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetStudentFromClassesName;
create procedure GetStudentFromClassesName (
    in fName varchar(15),
    in lName varchar(20),
    in tID int
)
begin
    select StudentID, Student.FirstName as FirstName,
    Student.MidInitial as MidInitial,
    Student.LastName as LastName,
    Student.Gender as Gender,
    Percentage, Num_Absences, ClassID
    from Student
    natural join Learns_In
    natural join ClassInSchool
    where TeacherID = tID
    and Student.FirstName = fName
    and Student.LastName = lName;
end
//
delimiter ;

delimiter //
drop procedure if exists GetInfoAboutClass;
create procedure GetInfoAboutClass (
    in cID varchar(15)
)
begin
    select avg(Percentage) as AveragePercentage,
    count(*) as StudentCount,
    (
        select count(*)
        from ClassInSchool
        natural join Learns_In
        natural join Student
        where ClassInSchool.ClassID = cID
        and Percentage >= 70
    )/(
        select count(*)
        from ClassInSchool
        natural join Learns_In
        natural join Student
        where ClassInSchool.ClassID = cID
    ) * 100 as PercentPassing,
    avg(Num_Suspensions) as AverageSuspensions,
    avg(Num_Detentions) as AverageDetentions,
    avg(Num_Absences) as AverageAbsences
    from ClassInSchool
    natural join Learns_In
    natural join Student
    where ClassInSchool.ClassID = cID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetClassStatisticsByTeacher;
create procedure GetClassStatisticsByTeacher (
    in tID int
)
begin
    select ClassID, isHonorsOrAP, round(avg(Percentage), 2) as AveragePercentage
    from ( 
        select ClassID, concat(FirstName, ' ', LastName) as Teacher, isHonorsOrAP, Percentage
        from ClassInSchool
        natural join Teacher
        natural join Learns_In
        where Teacher.TeacherID = tID
    ) as ThatTable
    group by ClassID;
end
//
delimiter ;

delimiter //
drop procedure if exists GetAllTeacherStudentData;
create procedure GetAllTeacherStudentData (
    in tID int
)
begin
    select count(*) as StudentCount,
    avg(Percentage) as AveragePercentage 
    from Teacher
    natural join ClassInSchool
    natural join Learns_In
    cross join Student on Learns_In.StudentID = Student.StudentID
    where Teacher.TeacherID = tID;
end
//
delimiter ;


