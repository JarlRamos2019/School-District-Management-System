create table Learns_In_Two (
    StudentID int unsigned,
    SubjectID varchar(4),
    LevelNumber int unsigned,
    Percentage float,
    primary key (StudentID, SubjectID, LevelNumber),
    foreign key (StudentID) references Student(StudentID),
    foreign key (SubjectID, LevelNumber) references ClassInSchool(SubjectID, LevelNumber)
);

