-- Mostafa Abadi, Jarl Ramos, Paramveer Takkar, Alexis Zurita
-- sdms.sql
-- 19 October 2023
-- CMPS-3420
-- Description: Implements school district database in SQL

set foreign_key_checks = 0;

drop table if exists District;

create table District (
    Name varchar(50) primary key not null check (Name <> '')
);

drop table if exists School;

create table School (
    Name varchar(50) primary key not null check (Name <> ''),
    DistrictName varchar(50),
    foreign key (DistrictName) references District(Name)
);

drop table if exists Teacher;

create table Teacher (
    TeacherID int unsigned not null auto_increment,
    SchoolName varchar(50),
    SatisfactionLevel int unsigned,
    Pay int unsigned,
    DateHired date,
    FirstName varchar(15),
    MidInitial char,
    LastName varchar(15),
    primary key (TeacherID),
    foreign key (SchoolName) references School(Name)
);

drop table if exists ClassInSchool;

create table ClassInSchool (
    SubjectID varchar(4),
    LevelNumber int unsigned not null,
    Subject varchar(30),
    isHonorsOrAP boolean,
    TeacherID int unsigned,
    primary key (SubjectID, LevelNumber, TeacherID),
    foreign key (TeacherID) references Teacher(TeacherID)
);

drop table if exists Student;

create table Student (
    StudentID int unsigned not null auto_increment primary key,
    F_Hardship int unsigned,
    Grade int unsigned,
    GPA float,
    FirstName varchar(15),
    MidInitial char,
    LastName varchar(15),
    Gender varchar(20),
    Num_Suspensions int unsigned,
    Num_Detentions int unsigned,
    Num_Absences int unsigned
);

drop table if exists Learns_In;

create table Learns_In (
    StudentID int unsigned,
    ClassID varchar(15),
    Percentage float,
    primary key (StudentID, ClassID),
    foreign key (StudentID) references Student(StudentID),
    foreign key (ClassID) references ClassMapper(ClassID)
);

drop table if exists Extracurriculars;

create table Extracurriculars (
    StudentID int unsigned,
    ExtraName varchar(30),
    primary key (StudentID, ExtraName),
    foreign key (StudentID) references Student(StudentID)
);

drop table if exists ClassMapper;

create table ClassMapper (
    ClassID varchar(15) primary key
);

set foreign_key_checks = 1;

alter table Teacher auto_increment=10000000;
alter table Student auto_increment=1000000000;

