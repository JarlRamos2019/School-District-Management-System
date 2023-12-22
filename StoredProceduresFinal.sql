SET foreign_key_checks = 0;

DELIMITER //
--(1) Stored Procedure for Inserting a New Teacher Record:
DROP PROCEDURE IF EXISTS InsertNewTeacherRecord;
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
    INSERT INTO Teacher (SchoolName, TeacherID, SatisfactionLevel, Pay, DateHired, FirstName, MidInitial, LastName)
    VALUES (paramSchoolName, paramTeacherID, paramSatisfactionLevel, paramPay, paramDateHired, paramFirstName, paramMiddleInitial, paramLastName);

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

-- To execute this procedure:
-- Call the stored procedure to insert a new teacher record
-- CALL InsertNewTeacherRecord('Sample School', 1, 5, 50000.00, '2023-01-01', 'John', 'D', 'Doe');

DELIMITER //
--(2) Stored Procedure for Deleting a ClassInSchool Record:
DROP PROCEDURE IF EXISTS DeleteClassInSchoolRecord;
CREATE PROCEDURE DeleteClassInSchoolRecord(
    IN IDClass varchar(15)
)
BEGIN
    -- Start a transaction
    START TRANSACTION;

    -- Delete an existing ClassInSchool record based on ClassID
    DELETE FROM ClassInSchool
    WHERE ClassID = IDClass;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to delete a ClassInSchool record
-- CALL DeleteClassInSchoolRecord("CHEM-1000");

DELIMITER //
--(3) Stored Procedure for Updating a Student Record:
DROP PROCEDURE IF EXISTS UpdateStudentRecordByStudentID;
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
    SET F_Hardship = paramNewFinancialHardship, Grade = paramNewGrade, GPA = paramNewGPA
    WHERE StudentID = paramStudentID;

    -- Commit the transaction
    COMMIT;
END;
//
DELIMITER ;

--To execute this procedure:
-- Call the stored procedure to update a Student record by StudentID
-- CALL UpdateStudentRecordByStudentID(1, 900.00, 11, 3.85);

DELIMITER //
--(4) Stored Procedure for Returning Statistics Regarding Teacher Employment over
-- a Time Interval
DROP PROCEDURE IF EXISTS GetStatisticsForClassPercentage;
CREATE PROCEDURE GetStatisticsForClassPercentage(
    IN TargetClass VARCHAR(15)
)
BEGIN
    -- Calculate Number of Teachers who were hired as of the specified target date
    SELECT ROUND(AVG(Percentage), 1) AS AvgPercentage
    FROM ClassInSchool NATURAL JOIN Learns_In
    WHERE ClassID = TargetClass;
END;
//
DELIMITER ;

SET foreign_key_checks = 1;
