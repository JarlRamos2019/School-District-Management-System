DELIMITER //


--Stored Procedure for Inserting a New Teacher Record:


CREATE PROCEDURE InsertNewTeacherRecord(
    IN paramSchoolName VARCHAR(255),
    IN paramTeacherID INT,
    IN paramSatisfactionLevel INT,
    IN paramPay DECIMAL(10, 2),
    IN paramDateHired DATE,
    IN paramFirstName VARCHAR(255),
    IN paramMiddleInitial CHAR(1),
    IN paramLastName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Insert a new teacher record into the Teacher table
    INSERT INTO Teacher (SchoolName, TeacherID, SatisfactionLevel, Pay, DateHired, FirstName, MiddleInitial, LastName)
    VALUES (paramSchoolName, paramTeacherID, paramSatisfactionLevel, paramPay, paramDateHired, paramFirstName, paramMiddleInitial, paramLastName);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to insert a new teacher record
CALL InsertNewTeacherRecord('Sample School', 1, 5, 50000.00, '2023-01-01', 'John', 'D', 'Doe');


--Stored Procedure for Updating an Existing Teacher Record:
DELIMITER //

CREATE PROCEDURE UpdateTeacherRecordByTeacherID(
    IN paramTeacherID INT,
    IN paramNewSatisfactionLevel INT,
    IN paramNewPay DECIMAL(10, 2)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Update an existing teacher record based on TeacherID
    UPDATE Teacher
    SET SatisfactionLevel = paramNewSatisfactionLevel, Pay = paramNewPay
    WHERE TeacherID = paramTeacherID;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;


--To execute this procedure:
-- Call the stored procedure to update a teacher record by TeacherID
CALL UpdateTeacherRecordByTeacherID(1, 4, 55000.00);



--Stored Procedure for Deleting an Existing Teacher Record:
DELIMITER //

CREATE PROCEDURE DeleteTeacherRecordByTeacherID(IN paramTeacherID INT)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing teacher record based on TeacherID
    DELETE FROM Teacher
    WHERE TeacherID = paramTeacherID;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;



--To execute this procedure:
-- Call the stored procedure to delete a teacher record by TeacherID
CALL DeleteTeacherRecordByTeacherID(1);



--2). ClassInSchool
--Stored Procedure for Inserting a New ClassInSchool Record:
DELIMITER //

CREATE PROCEDURE InsertNewClassInSchoolRecord(
    IN paramSubjectID INT,
    IN paramLevelNumber INT,
    IN paramTeacherID INT,
    IN paramSubject VARCHAR(255),
    IN paramIsHonorsOrAP BOOLEAN
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Insert a new ClassInSchool record into the ClassInSchool table
    INSERT INTO ClassInSchool (SubjectID, LevelNumber, TeacherID, Subject, isHonorsOrAP)
    VALUES (paramSubjectID, paramLevelNumber, paramTeacherID, paramSubject, paramIsHonorsOrAP);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;



--To execute this procedure:
-- Call the stored procedure to insert a new ClassInSchool record
CALL InsertNewClassInSchoolRecord(1, 10, 1, 'Math', 1);


--Stored Procedure for Updating an Existing ClassInSchool Record:
DELIMITER //

CREATE PROCEDURE UpdateClassInSchoolRecord(
    IN paramSubjectID INT,
    IN paramLevelNumber INT,
    IN paramNewSubject VARCHAR(255),
    IN paramNewIsHonorsOrAP BOOLEAN
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Update an existing ClassInSchool record based on SubjectID and LevelNumber
    UPDATE ClassInSchool
    SET Subject = paramNewSubject, isHonorsOrAP = paramNewIsHonorsOrAP
    WHERE SubjectID = paramSubjectID AND LevelNumber = paramLevelNumber;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;


--To execute this procedure:

-- Call the stored procedure to update a ClassInSchool record
CALL UpdateClassInSchoolRecord(1, 10, 'Advanced Math', 1);


--Stored Procedure for Deleting an Existing ClassInSchool Record:

DELIMITER //

CREATE PROCEDURE DeleteClassInSchoolRecord(
    IN paramSubjectID INT,
    IN paramLevelNumber INT
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing ClassInSchool record based on SubjectID and LevelNumber
    DELETE FROM ClassInSchool
    WHERE SubjectID = paramSubjectID AND LevelNumber = paramLevelNumber;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;


--To execute this procedure:

-- Call the stored procedure to delete a ClassInSchool record
CALL DeleteClassInSchoolRecord(1, 10);


--3). Stored Procedure for Inserting a New District Record:

DELIMITER //

CREATE PROCEDURE InsertNewDistrictRecord(
    IN paramDistrictName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Insert a new District record into the District table
    INSERT INTO District (Name)
    VALUES (paramDistrictName);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to insert a new District record
CALL InsertNewDistrictRecord('Sample District');


--Stored Procedure for Updating an Existing District Record:
DELIMITER //

CREATE PROCEDURE UpdateDistrictRecord(
    IN paramDistrictName VARCHAR(255),
    IN paramNewDistrictName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Update an existing District record based on DistrictName
    UPDATE District
    SET Name = paramNewDistrictName
    WHERE Name = paramDistrictName;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to update a District record
CALL UpdateDistrictRecord('Sample District', 'Updated District Name');


--Stored Procedure for Deleting an Existing District Record:
DELIMITER //

CREATE PROCEDURE DeleteDistrictRecord(
    IN paramDistrictName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing District record based on DistrictName
    DELETE FROM District
    WHERE Name = paramDistrictName;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to delete a District record
CALL DeleteDistrictRecord('Sample District');


--4).Stored Procedure for Inserting a New School Record:
--DELIMITER //

CREATE PROCEDURE InsertNewSchoolRecord(
    IN paramSchoolName VARCHAR(255),
    IN paramDistrictName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Insert a new School record into the School table
    INSERT INTO School (Name, DistrictName)
    VALUES (paramSchoolName, paramDistrictName);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;


--To execute this procedure:
-- Call the stored procedure to insert a new School record
CALL InsertNewSchoolRecord('Sample School', 'Sample District');


--Stored Procedure for Updating an Existing School Record:
DELIMITER //

CREATE PROCEDURE UpdateSchoolRecord(
    IN paramSchoolName VARCHAR(255),
    IN paramNewDistrictName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Update an existing School record based on SchoolName
    UPDATE School
    SET DistrictName = paramNewDistrictName
    WHERE Name = paramSchoolName;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to update a School record
CALL UpdateSchoolRecord('Sample School', 'Updated District');


--Stored Procedure for Deleting an Existing School Record:

DELIMITER //

CREATE PROCEDURE DeleteSchoolRecord(
    IN paramSchoolName VARCHAR(255)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing School record based on SchoolName
    DELETE FROM School
    WHERE Name = paramSchoolName;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to delete a School record
CALL DeleteSchoolRecord('Sample School');


--5). Student
--Stored Procedure for Inserting a New Student Record:

DELIMITER //

CREATE PROCEDURE InsertNewStudentRecord(
    IN paramStudentID INT,
    IN paramFinancialHardship DECIMAL(10, 2),
    IN paramGrade INT,
    IN paramGPA DECIMAL(3, 2),
    IN paramFirstName VARCHAR(255),
    IN paramMiddleInitial CHAR(1),
    IN paramLastName VARCHAR(255),
    IN paramGender CHAR(1),
    IN paramNumSuspensions INT,
    IN paramNumDetentions INT,
    IN paramNumAbsences INT
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Insert a new Student record into the Student table
    INSERT INTO Student (StudentID, Financialardship, Grade, GPA, FirstName, Midlnitial, LastName, Gender, Num_Suspensions, Num_Detentions, NumAbsences)
    VALUES (paramStudentID, paramFinancialHardship, paramGrade, paramGPA, paramFirstName, paramMiddleInitial, paramLastName, paramGender, paramNumSuspensions, paramNumDetentions, paramNumAbsences);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;


--To execute this procedure:

-- Call the stored procedure to insert a new Student record
CALL InsertNewStudentRecord(1, 1000.00, 10, 3.75, 'John', 'D', 'Doe', 'M', 2, 1, 5);


--Stored Procedure for Updating an Existing Student Record:
DELIMITER //

CREATE PROCEDURE UpdateStudentRecordByStudentID(
    IN paramStudentID INT,
    IN paramNewFinancialHardship DECIMAL(10, 2),
    IN paramNewGrade INT,
    IN paramNewGPA DECIMAL(3, 2)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Update an existing Student record based on StudentID
    UPDATE Student
    SET Financialardship = paramNewFinancialHardship, Grade = paramNewGrade, GPA = paramNewGPA
    WHERE StudentID = paramStudentID;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to update a Student record by StudentID
CALL UpdateStudentRecordByStudentID(1, 900.00, 11, 3.85);


--Stored Procedure for Deleting an Existing Student Record:
DELIMITER //

CREATE PROCEDURE DeleteStudentRecordByStudentID(
    IN paramStudentID INT
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing Student record based on StudentID
    DELETE FROM Student
    WHERE StudentID = paramStudentID;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:

-- Call the stored procedure to delete a Student record by StudentID
CALL DeleteStudentRecordByStudentID(1);



--6). Learn_In
--Stored Procedure for Inserting a New Record into the "Learns In" Table:

CREATE PROCEDURE InsertLearnsInRecord
    @StudentID INT,
    @SubjectID INT,
    @LevelNumber INT,
    @Percentage DECIMAL(5, 2)
AS
BEGIN
    INSERT INTO LearnsIn (StudentID, SubjectID, LevelNumber, Percentage)
    VALUES (@StudentID, @SubjectID, @LevelNumber, @Percentage);
END


--Stored Procedure for Deleting an Existing Record from the "Learns In"
CREATE PROCEDURE DeleteLearnsInRecord
    @StudentID INT,
    @SubjectID INT
AS
BEGIN
    DELETE FROM LearnsIn
    WHERE StudentID = @StudentID AND SubjectID = @SubjectID;
END


--Stored Procedure for Updating an Existing Record in the "Learns In"

CREATE PROCEDURE UpdateLearnsInRecord
    @StudentID INT,
    @SubjectID INT,
    @NewLevelNumber INT,
    @NewPercentage DECIMAL(5, 2)
AS
BEGIN
    UPDATE LearnsIn
    SET LevelNumber = @NewLevelNumber, Percentage = @NewPercentage
    WHERE StudentID = @StudentID AND SubjectID = @SubjectID;
END

--Stored Procedure for Returning Statistical Metrics with Parameters:
CREATE PROCEDURE GetStudentStatistics
    @StudentID INT
AS
BEGIN
    DECLARE @LastMonth DATE = DATEADD(MONTH, -1, GETDATE());

    -- Calculate Average Percentage for the Last Month for a specific student
    SELECT AVG(Percentage) AS AveragePercentageLastMonth
    FROM LearnsIn
    WHERE StudentID = @StudentID AND Date >= @LastMonth;

    -- List of Extracurriculars for the Student
    SELECT ExtraName
    FROM Extracurriculars
    WHERE StudentID = @StudentID;

END


--7). Stored Procedure for Inserting a New Record into the "Extracurriculars" Table:

CREATE PROCEDURE InsertExtracurricularRecord
    @StudentID INT,
    @ExtraName VARCHAR(255) -- Adjust the data type as needed
AS
BEGIN
    INSERT INTO Extracurriculars (StudentID, ExtraName)
    VALUES (@StudentID, @ExtraName);
END


--Stored Procedure for Deleting an Existing Record from the "Extracurriculars" Table Based on Primary Key:

CREATE PROCEDURE DeleteExtracurricularRecord
    @StudentID INT,
    @ExtraName VARCHAR(255) -- Adjust the data type as needed
AS
BEGIN
    DELETE FROM Extracurriculars
    WHERE StudentID = @StudentID AND ExtraName = @ExtraName;
END


--Stored Procedure for Updating an Existing Record in the "Extracurriculars" 
CREATE PROCEDURE UpdateExtracurricularRecord
    @StudentID INT,
    @OldExtraName VARCHAR(255), -- Adjust the data type as needed
    @NewExtraName VARCHAR(255) -- Adjust the data type as needed
AS
BEGIN
    UPDATE Extracurriculars
    SET ExtraName = @NewExtraName
    WHERE StudentID = @StudentID AND ExtraName = @OldExtraName;
END

