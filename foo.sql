create table Learns_In (
    StudentID varchar(10),
    SubjectID varchar(4),
    LevelNumber int unsigned,
    Percentage float,
    primary key (StudentID, SubjectID, LevelNumber),
    foreign key (SubjectID, LevelNumber) references ClassInSchool(SubjectID, LevelNumber)
);