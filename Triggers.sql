
--Trigger for Deleting a Row:
--Create a table for Teacher:
CREATE TABLE Teacher (
    SchoolName VARCHAR(50),
    TeacherID INT PRIMARY KEY,
    SatisfactionLevel INT,
    Pay DECIMAL(10, 2),
    DateHired DATE,
    First_Name VARCHAR(50),
    Middle_Initial CHAR(1),
    Last_Name VARCHAR(50)
);

--Create a table for TeacherHistory:
CREATE TABLE TeacherHistory (
    TeacherID INT,
    DeletedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


--Create a trigger for deleting a row and inserting into TeacherHistory:
CREATE OR REPLACE FUNCTION delete_teacher_trigger()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO TeacherHistory (TeacherID) VALUES (OLD.TeacherID);
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER on_delete_teacher
BEFORE DELETE ON Teacher
FOR EACH ROW
EXECUTE FUNCTION delete_teacher_trigger();




-- Insert some data into Teacher
INSERT INTO Teacher VALUES ('SchoolA', 1, 5, 50000, '2023-01-01', 'John', 'D', 'Doe');

-- Check Teacher and TeacherHistory tables before delete
SELECT * FROM Teacher;
SELECT * FROM TeacherHistory;

-- Delete a row from Teacher
DELETE FROM Teacher WHERE TeacherID = 1;

-- Check Teacher and TeacherHistory tables after delete
SELECT * FROM Teacher;
SELECT * FROM TeacherHistory;




---Trigger for Updating a Row:

-- Assuming you have a related table with a foreign key constraint
ALTER TABLE RelatedTable
ADD CONSTRAINT fk_teacher FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID) ON UPDATE CASCADE;


-- Update TeacherID in Teacher table
UPDATE Teacher SET TeacherID = 2 WHERE TeacherID = 1;

-- Check related tables to see if the update cascaded
SELECT * FROM RelatedTable;


--Trigger for Inserting a Row:
CREATE OR REPLACE FUNCTION insert_teacher_trigger()
RETURNS TRIGGER AS $$
BEGIN
    -- Your trigger logic for insert
    -- For example, you can log the inserted row in another table
    INSERT INTO LogTable (Message) VALUES ('New row inserted into Teacher table');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER on_insert_teacher
AFTER INSERT ON Teacher
FOR EACH ROW
EXECUTE FUNCTION insert_teacher_trigger();


-- Insert a row into Teacher
INSERT INTO Teacher VALUES ('SchoolB', 3, 4, 45000, '2023-02-01', 'Jane', 'M', 'Smith');

-- Check LogTable to see if the trigger fired
SELECT * FROM LogTable;

