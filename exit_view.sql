drop view if exists ExtraList;

create view ExtraList as
select concat(FirstName, ' ', LastName) as Student,
ExtraName as Activity
from Student natural join Extracurriculars;
